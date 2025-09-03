<!-- Comments Section -->
<section class="form-section" id="section-comments">
    <h3 class="mb-4">Comments & Recommendations</h3>

    <div class="row">
        <div class="col-12">
            <table class="table table-bordered">
                <thead class="table-light">
                    <tr>
                        <th>Field</th>
                        <th>Information</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong>Inspector Comments</strong></td>
                        <td>
                            <textarea name="inspector_comments" class="form-control" rows="4" 
                                      placeholder="Enter inspector observations and comments"></textarea>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Recommendations</strong></td>
                        <td>
                            <textarea name="recommendations" class="form-control" rows="4" 
                                      placeholder="Enter recommendations for maintenance, repairs, or follow-up actions"></textarea>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Defects Found</strong></td>
                        <td>
                            <textarea name="defects_found" class="form-control" rows="3" 
                                      placeholder="Describe any defects or issues identified"></textarea>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Overall Result</strong></td>
                        <td>
                            <select name="overall_result" class="form-select">
                                <option value="">Select result...</option>
                                <option value="pass">Pass</option>
                                <option value="fail">Fail</option>
                                <option value="conditional_pass">Conditional Pass</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Next Inspection Date</strong></td>
                        <td>
                            <input type="date" name="next_inspection_date" class="form-control">
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</section>

<style>
.comment-category {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 8px;
    border-left: 4px solid #6f42c1;
    margin-bottom: 20px;
}

.category-title {
    color: #6f42c1;
    font-size: 1.1rem;
    margin-bottom: 15px;
    font-weight: 600;
}

.form-check-group {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.form-check-group .form-check {
    margin-bottom: 0;
}

.signature-pad canvas {
    cursor: crosshair;
    background-color: white;
}

.signature-pad canvas:hover {
    border-color: #0d6efd;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize signature pads only if they exist
    try {
        if (document.querySelector('#inspector-signature')) {
            initializeSignaturePad('inspector-signature', 'inspector_signature_data');
        }
        if (document.querySelector('#supervisor-signature')) {
            initializeSignaturePad('supervisor-signature', 'supervisor_signature_data');
        }
    } catch (error) {
        console.log('Signature pads not found or not needed:', error.message);
    }
    
    function initializeSignaturePad(containerId, dataInputId) {
        const container = document.querySelector(`#${containerId}`);
        if (!container) {
            console.log(`Signature pad container ${containerId} not found`);
            return;
        }
        
        const canvas = container.querySelector('canvas');
        if (!canvas) {
            console.log(`Canvas not found in ${containerId}`);
            return;
        }
        
        const ctx = canvas.getContext('2d');
        if (!ctx) {
            console.log(`Could not get 2d context for ${containerId}`);
            return;
        }
        
        let isDrawing = false;
        
        // Set canvas size
        const rect = canvas.getBoundingClientRect();
        canvas.width = rect.width * window.devicePixelRatio;
        canvas.height = rect.height * window.devicePixelRatio;
        ctx.scale(window.devicePixelRatio, window.devicePixelRatio);
        
        // Style the drawing
        ctx.strokeStyle = '#000';
        ctx.lineWidth = 2;
        ctx.lineCap = 'round';
        ctx.lineJoin = 'round';
        
        function startDrawing(e) {
            isDrawing = true;
            const rect = canvas.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;
            ctx.beginPath();
            ctx.moveTo(x, y);
        }
        
        function draw(e) {
            if (!isDrawing) return;
            const rect = canvas.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;
            ctx.lineTo(x, y);
            ctx.stroke();
            
            // Save signature data
            document.getElementById(dataInputId).value = canvas.toDataURL();
        }
        
        function stopDrawing() {
            isDrawing = false;
        }
        
        // Mouse events
        canvas.addEventListener('mousedown', startDrawing);
        canvas.addEventListener('mousemove', draw);
        canvas.addEventListener('mouseup', stopDrawing);
        canvas.addEventListener('mouseout', stopDrawing);
        
        // Touch events for mobile
        canvas.addEventListener('touchstart', function(e) {
            e.preventDefault();
            const touch = e.touches[0];
            const mouseEvent = new MouseEvent('mousedown', {
                clientX: touch.clientX,
                clientY: touch.clientY
            });
            canvas.dispatchEvent(mouseEvent);
        });
        
        canvas.addEventListener('touchmove', function(e) {
            e.preventDefault();
            const touch = e.touches[0];
            const mouseEvent = new MouseEvent('mousemove', {
                clientX: touch.clientX,
                clientY: touch.clientY
            });
            canvas.dispatchEvent(mouseEvent);
        });
        
        canvas.addEventListener('touchend', function(e) {
            e.preventDefault();
            const mouseEvent = new MouseEvent('mouseup', {});
            canvas.dispatchEvent(mouseEvent);
        });
    }
});

function clearSignature(containerId) {
    const canvas = document.querySelector(`#${containerId} canvas`);
    const ctx = canvas.getContext('2d');
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    
    // Clear the hidden input
    const dataInputId = containerId.replace('-signature', '_signature_data');
    document.getElementById(dataInputId).value = '';
}
</script>
