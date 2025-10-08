<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use App\Http\Requests\StoreClientRequest;
use App\Http\Requests\UpdateClientRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ClientController extends Controller
{
    /**
     * Display a listing of clients (Super Admin only)
     */
    public function index(Request $request)
    {
        $query = Client::query();
        
        // Search functionality
        if ($request->has('search') && $request->search) {
            $query->search($request->search);
        }
        
        // Filter by status
        if ($request->has('status')) {
            if ($request->status === 'active') {
                $query->active();
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }
        
        $clients = $query->orderBy('client_name')->paginate(15);
        
        return view('admin.clients.index', compact('clients'));
    }

    /**
     * Show the form for creating a new client
     */
    public function create()
    {
        return view('admin.clients.create');
    }

    /**
     * Store a newly created client
     */
    public function store(StoreClientRequest $request)
    {
        $validated = $request->validated();

        if (!array_key_exists('is_active', $validated) || $validated['is_active'] === null) {
            unset($validated['is_active']);
        }

        try {
            DB::beginTransaction();

            $client = new Client($validated);

            if (empty($client->client_code)) {
                $client->client_code = $client->generateClientCode();
            }

            $client->save();

            DB::commit();

            return redirect()->route('admin.clients.index')
                ->with('success', 'Client created successfully.');

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->with('error', 'Failed to create client: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified client
     */
    public function show(Client $client)
    {
        $client->load('inspections');
        return view('admin.clients.show', compact('client'));
    }

    /**
     * Show the form for editing the specified client
     */
    public function edit(Client $client)
    {
        return view('admin.clients.edit', compact('client'));
    }

    /**
     * Update the specified client
     */
    public function update(UpdateClientRequest $request, Client $client)
    {
        $validated = $request->validated();

        if (!array_key_exists('is_active', $validated) || $validated['is_active'] === null) {
            unset($validated['is_active']);
        }

        try {
            DB::beginTransaction();

            $client->update($validated);

            DB::commit();

            return redirect()->route('admin.clients.index')
                ->with('success', 'Client updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->with('error', 'Failed to update client: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified client
     */
    public function destroy(Client $client)
    {
        try {
            // Check if client has inspections
            if ($client->inspections()->count() > 0) {
                return redirect()->back()
                    ->with('error', 'Cannot delete client with existing inspections. Consider deactivating instead.');
            }
            
            $client->delete();
            
            return redirect()->route('admin.clients.index')
                ->with('success', 'Client deleted successfully.');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to delete client: ' . $e->getMessage());
        }
    }

    /**
     * Toggle client status
     */
    public function toggleStatus(Client $client)
    {
        try {
            $client->update(['is_active' => !$client->is_active]);
            
            $status = $client->is_active ? 'activated' : 'deactivated';
            
            return redirect()->back()
                ->with('success', "Client {$status} successfully.");
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to update client status: ' . $e->getMessage());
        }
    }

    /**
     * Get clients for API (for dropdowns)
     */
    public function getClients(Request $request)
    {
        try {
            // Early exit if table doesn't exist (prevents 500 during setup)
            if (!Schema::hasTable('clients')) {
                return response()->json([]);
            }
            // Helper to run the base select + mapping on a given builder
            $runQuery = function ($builder, $request) {
                // Search functionality for AJAX requests
                if ($request->filled('search')) {
                    $search = $request->input('search');
                    $builder->where(function ($q) use ($search) {
                        $q->where('client_name', 'like', "%{$search}%")
                          ->orWhere('client_code', 'like', "%{$search}%")
                          ->orWhere('contact_person', 'like', "%{$search}%");
                    });
                }

                return $builder->select([
                    'id',
                    'client_name',
                    'client_code',
                    'company_type',
                    'industry',
                    'address',
                    'city',
                    'state',
                    'country',
                    'postal_code',
                    'phone',
                    'email',
                    'website',
                    'contact_person',
                    'contact_position',
                    'contact_phone',
                    'contact_email',
                    'payment_terms',
                    'preferred_currency',
                    'notes',
                ])
                ->orderBy('client_name')
                ->limit(50)
                ->get()
                ->map(function ($c) {
                    $display = $c->client_code
                        ? $c->client_name . ' (' . $c->client_code . ')'
                        : $c->client_name;

                    $fullAddressParts = array_filter([
                        $c->address,
                        $c->city,
                        $c->state,
                        trim(($c->postal_code ?? '') . ''),
                        $c->country,
                    ], function ($v) { return (string) $v !== ''; });

                    $c->display_name = $display;
                    $c->full_address = implode(', ', $fullAddressParts);
                    return $c;
                })
                ->values();
            };

            // First, try default connection
            $defaultBuilder = DB::table('clients');
            $clients = $runQuery($defaultBuilder, $request);

            // If empty, try MySQL connection as a fallback (useful if .env points to sqlite)
            if ($clients->isEmpty() && config('database.connections.mysql')) {
                try {
                    $mysqlBuilder = DB::connection('mysql')->table('clients');
                    $mysqlClients = $runQuery($mysqlBuilder, $request);
                    if ($mysqlClients->isNotEmpty()) {
                        $clients = $mysqlClients;
                    }
                    // If still empty, attempt forcing a specific MySQL database name (e.g., 'sc')
                    if ($clients->isEmpty()) {
                        $forcedDb = env('DB_MYSQL_DATABASE', 'sc');
                        $currentDb = config('database.connections.mysql.database');
                        if ($forcedDb && $forcedDb !== $currentDb) {
                            config(['database.connections.mysql.database' => $forcedDb]);
                            $mysqlForced = DB::connection('mysql')->table('clients');
                            $forcedClients = $runQuery($mysqlForced, $request);
                            if ($forcedClients->isNotEmpty()) {
                                $clients = $forcedClients;
                            }
                        }
                    }
                } catch (\Throwable $t) {
                    // Ignore if mysql connection is not configured or fails
                    \Log::info('MySQL fallback for clients failed or not configured', [
                        'message' => $t->getMessage(),
                    ]);
                }
            }

            return response()->json([
                'success' => true,
                'clients' => $clients
            ]);
            
        } catch (\Exception $e) {
            \Log::error('getClients failed', [
                'exception' => get_class($e),
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json([
                'error' => 'Failed to fetch clients: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get specific client data for form population
     */
    public function getClientData(Request $request, $id)
    {
        try {
            $client = Client::findOrFail($id);
            
            return response()->json([
                'success' => true,
                'client' => $client->toArray()
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Client not found: ' . $e->getMessage()
            ], 404);
        }
    }
}

