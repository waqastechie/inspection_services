<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

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
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'client_name' => 'required|string|max:255|unique:clients,client_name',
            'client_code' => 'nullable|string|max:10|unique:clients,client_code',
            'company_type' => 'nullable|string|max:100',
            'industry' => 'nullable|string|max:100',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:255',
            'contact_person' => 'nullable|string|max:255',
            'contact_position' => 'nullable|string|max:100',
            'contact_phone' => 'nullable|string|max:20',
            'contact_email' => 'nullable|email|max:255',
            'billing_address' => 'nullable|string|max:500',
            'billing_city' => 'nullable|string|max:100',
            'billing_state' => 'nullable|string|max:100',
            'billing_country' => 'nullable|string|max:100',
            'billing_postal_code' => 'nullable|string|max:20',
            'tax_id' => 'nullable|string|max:50',
            'registration_number' => 'nullable|string|max:50',
            'payment_terms' => 'nullable|string|max:50',
            'credit_limit' => 'nullable|numeric|min:0',
            'preferred_currency' => 'nullable|string|size:3',
            'notes' => 'nullable|string|max:1000',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();
            
            $client = new Client($request->all());
            
            // Generate client code if not provided
            if (!$request->client_code) {
                $client->client_code = $client->generateClientCode();
            }
            
            $client->save();
            
            DB::commit();
            
            return redirect()->route('admin.clients.index')
                ->with('success', 'Client created successfully.');
                
        } catch (\Exception $e) {
            DB::rollback();
            
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
    public function update(Request $request, Client $client)
    {
        $validator = Validator::make($request->all(), [
            'client_name' => 'required|string|max:255|unique:clients,client_name,' . $client->id,
            'client_code' => 'nullable|string|max:10|unique:clients,client_code,' . $client->id,
            'company_type' => 'nullable|string|max:100',
            'industry' => 'nullable|string|max:100',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:255',
            'contact_person' => 'nullable|string|max:255',
            'contact_position' => 'nullable|string|max:100',
            'contact_phone' => 'nullable|string|max:20',
            'contact_email' => 'nullable|email|max:255',
            'billing_address' => 'nullable|string|max:500',
            'billing_city' => 'nullable|string|max:100',
            'billing_state' => 'nullable|string|max:100',
            'billing_country' => 'nullable|string|max:100',
            'billing_postal_code' => 'nullable|string|max:20',
            'tax_id' => 'nullable|string|max:50',
            'registration_number' => 'nullable|string|max:50',
            'payment_terms' => 'nullable|string|max:50',
            'credit_limit' => 'nullable|numeric|min:0',
            'preferred_currency' => 'nullable|string|size:3',
            'notes' => 'nullable|string|max:1000',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();
            
            $client->update($request->all());
            
            DB::commit();
            
            return redirect()->route('admin.clients.index')
                ->with('success', 'Client updated successfully.');
                
        } catch (\Exception $e) {
            DB::rollback();
            
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
            $query = Client::active();
            
            // Search functionality for AJAX requests
            if ($request->has('search') && $request->search) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('client_name', 'like', "%{$search}%")
                      ->orWhere('client_code', 'like', "%{$search}%")
                      ->orWhere('contact_person', 'like', "%{$search}%");
                });
            }
            
            $clients = $query->select([
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
                'notes'
            ])
            ->orderBy('client_name')
            ->limit(50)
            ->get();
            
            // Add computed fields
            $clients->each(function ($client) {
                $client->display_name = $client->display_name;
                $client->full_address = $client->full_address;
            });
            
            return response()->json($clients);
            
        } catch (\Exception $e) {
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
