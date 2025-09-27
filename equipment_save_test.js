// Test JavaScript to verify equipment saving - paste in browser console
(function testEquipmentSave() {
    console.log('Testing equipment save functionality...');
    
    // Check if required functions exist
    if (typeof window.step4SaveAndContinue !== 'function') {
        console.error('❌ window.step4SaveAndContinue function not found');
        return;
    }
    
    if (typeof collectTableData !== 'function') {
        console.error('❌ collectTableData function not found');
        return;
    }
    
    console.log('✅ Required functions found');
    
    // Test data collection
    const testData = collectTableData();
    console.log('Current table data:', testData);
    
    if (testData.length === 0) {
        console.log('⚠️ No data in table to save. Add some equipment first.');
        return;
    }
    
    // Test inspection ID retrieval
    const inspectionId = window.currentInspectionId || getInspectionId();
    const clientId = window.currentClientId || getClientId();
    
    console.log('Inspection ID:', inspectionId);
    console.log('Client ID:', clientId);
    
    if (!inspectionId) {
        console.error('❌ No inspection ID found');
        return;
    }
    
    console.log('✅ Ready to save equipment data');
    console.log('You can now click "Save & Continue" to test the actual save operation');
})();