<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

try {
    echo "Testing PDF generation for inspection #19...\n\n";
    
    // Get inspection data
    $inspection = \App\Models\Inspection::with([
        'services.results',
        'personnelAssignments.personnel',
        'equipmentAssignments.equipment', 
        'consumableAssignments.consumable',
        'inspectionResults'
    ])->findOrFail(19);
    
    echo "✓ Inspection data loaded successfully\n";
    echo "  Inspection Number: " . $inspection->inspection_number . "\n";
    echo "  Services Count: " . $inspection->services->count() . "\n";
    echo "  Images Count: " . (is_array($inspection->inspection_images) ? count($inspection->inspection_images) : count(json_decode($inspection->inspection_images, true))) . "\n\n";
    
    // Test DomPDF loading
    echo "Testing DomPDF...\n";
    
    // Try to load DomPDF
    $pdf = app('dompdf.wrapper');
    echo "✓ DomPDF wrapper loaded successfully\n";
    
    // Try to load the view
    echo "Loading PDF view...\n";
    $html = view('pdf.inspection_report', compact('inspection'))->render();
    echo "✓ PDF view rendered successfully\n";
    echo "  HTML length: " . strlen($html) . " characters\n\n";
    
    // Load HTML into PDF
    $pdf->loadHTML($html);
    echo "✓ HTML loaded into PDF successfully\n";
    
    // Set options
    $pdf->setPaper('A4', 'portrait');
    $pdf->setOptions([
        'isHtml5ParserEnabled' => true,
        'isPhpEnabled' => false,
        'isRemoteEnabled' => false,
        'defaultFont' => 'Arial'
    ]);
    echo "✓ PDF options set successfully\n";
    
    // Generate PDF
    echo "Generating PDF...\n";
    $pdfOutput = $pdf->output();
    echo "✓ PDF generated successfully!\n";
    echo "  PDF size: " . strlen($pdfOutput) . " bytes\n";
    
    // Save test PDF
    $filename = 'test_inspection_report.pdf';
    file_put_contents($filename, $pdfOutput);
    echo "✓ PDF saved as: $filename\n\n";
    
    echo "🎉 PDF generation test completed successfully!\n";
    echo "You can now try downloading the PDF from the web interface.\n";
    
} catch (\Exception $e) {
    echo "❌ Error occurred during PDF generation:\n";
    echo "Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n\n";
    
    echo "Stack trace:\n";
    echo $e->getTraceAsString() . "\n";
}
