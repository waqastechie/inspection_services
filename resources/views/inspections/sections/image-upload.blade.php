<!-- Image Upload Section -->
<section class="form-section" id="section-image-upload">
    <div class="section-header">
        <h2 class="section-title">
            <i class="fas fa-camera"></i>
            Images & Documentation
        </h2>
        <p class="section-description">
            Upload images and supporting documentation for this inspection.
        </p>
    </div>

    <div class="section-content">
        <!-- Upload Area -->
        <div class="upload-area mb-4">
            <div class="row">
                <div class="col-md-8">
                    <div class="drop-zone" id="dropZone">
                        <div class="drop-zone-content">
                            <i class="fas fa-cloud-upload-alt fa-3x text-muted mb-3"></i>
                            <h5>Drop files here or click to browse</h5>
                            <p class="text-muted">
                                Supported formats: JPG, PNG, PDF, DOC, DOCX<br>
                                Maximum file size: 10MB per file
                            </p>
                            <input type="file" id="fileInput" name="inspection_images[]" multiple 
                                   accept="image/*,.pdf,.doc,.docx" style="display: none;">
                            <button type="button" class="btn btn-primary" onclick="document.getElementById('fileInput').click()">
                                <i class="fas fa-plus me-2"></i>Select Files
                            </button>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="upload-info">
                        <h6><i class="fas fa-info-circle me-2"></i>Upload Guidelines</h6>
                        <ul class="list-unstyled">
                            <li><i class="fas fa-check text-success me-2"></i>Equipment photos</li>
                            <li><i class="fas fa-check text-success me-2"></i>Defect documentation</li>
                            <li><i class="fas fa-check text-success me-2"></i>Test certificates</li>
                            <li><i class="fas fa-check text-success me-2"></i>Supporting documents</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Preview Area -->
        <div class="preview-area" id="previewArea" style="display: none;">
            <h6><i class="fas fa-eye me-2"></i>Selected Files</h6>
            <div class="row" id="filePreviewContainer">
                <!-- File previews will be added here -->
            </div>
        </div>

        <!-- Existing Images (for edit mode) -->
        @if(isset($inspection) && $inspection->images && $inspection->images->count() > 0)
        <div class="existing-images mb-4">
            <h6><i class="fas fa-images me-2"></i>Existing Images</h6>
            <div class="row">
                @foreach($inspection->images as $image)
                <div class="col-md-3 mb-3">
                    <div class="card">
                        <div class="card-body p-2">
                            @if(in_array(strtolower(pathinfo($image->file_path, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'gif']))
                                <img src="{{ asset('storage/' . $image->file_path) }}" 
                                     class="img-fluid rounded mb-2" 
                                     alt="Inspection Image"
                                     style="max-height: 150px; width: 100%; object-fit: cover;">
                            @else
                                <div class="text-center py-3">
                                    <i class="fas fa-file-alt fa-3x text-muted"></i>
                                    <br>
                                    <small>{{ pathinfo($image->file_path, PATHINFO_EXTENSION) }}</small>
                                </div>
                            @endif
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted">{{ $image->caption ?? 'No description' }}</small>
                                <button type="button" class="btn btn-sm btn-outline-danger" 
                                        onclick="removeExistingImage({{ $image->id }})">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.getElementById('fileInput');
    const dropZone = document.getElementById('dropZone');
    const previewArea = document.getElementById('previewArea');
    const previewContainer = document.getElementById('filePreviewContainer');
    let selectedFiles = [];

    // File input change handler
    fileInput.addEventListener('change', function(e) {
        handleFiles(e.target.files);
    });

    // Drag and drop handlers
    dropZone.addEventListener('dragover', function(e) {
        e.preventDefault();
        dropZone.classList.add('drag-over');
    });

    dropZone.addEventListener('dragleave', function(e) {
        e.preventDefault();
        dropZone.classList.remove('drag-over');
    });

    dropZone.addEventListener('drop', function(e) {
        e.preventDefault();
        dropZone.classList.remove('drag-over');
        handleFiles(e.dataTransfer.files);
    });

    // Handle selected files
    function handleFiles(files) {
        Array.from(files).forEach(file => {
            // Validate file
            if (validateFile(file)) {
                selectedFiles.push(file);
                addFilePreview(file);
            }
        });
        
        updatePreviewArea();
        updateFileInput();
    }

    // Update the file input with current files
    function updateFileInput() {
        // Create a new DataTransfer object to hold files
        const dt = new DataTransfer();
        selectedFiles.forEach(file => dt.items.add(file));
        fileInput.files = dt.files;
    }

    // Validate file
    function validateFile(file) {
        const maxSize = 10 * 1024 * 1024; // 10MB
        const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 
                             'application/pdf', 'application/msword', 
                             'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];

        if (file.size > maxSize) {
            alert(`File "${file.name}" is too large. Maximum size is 10MB.`);
            return false;
        }

        if (!allowedTypes.includes(file.type)) {
            alert(`File "${file.name}" has an unsupported format.`);
            return false;
        }

        return true;
    }

    // Add file preview
    function addFilePreview(file) {
        const fileId = 'file_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9);
        const col = document.createElement('div');
        col.className = 'col-md-3 mb-3';
        col.setAttribute('data-file-id', fileId);

        const isImage = file.type.startsWith('image/');
        let preview = '';

        if (isImage) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = col.querySelector('.file-preview-img');
                if (img) {
                    img.src = e.target.result;
                }
            };
            reader.readAsDataURL(file);
            
            preview = `<img class="file-preview-img img-fluid rounded mb-2" 
                            style="max-height: 150px; width: 100%; object-fit: cover;" 
                            alt="Preview">`;
        } else {
            const extension = file.name.split('.').pop().toUpperCase();
            preview = `<div class="text-center py-3">
                        <i class="fas fa-file-alt fa-3x text-muted"></i>
                        <br>
                        <small>${extension}</small>
                       </div>`;
        }

        col.innerHTML = `
            <div class="card">
                <div class="card-body p-2">
                    ${preview}
                    <div class="file-info">
                        <small class="text-muted d-block">${file.name}</small>
                        <small class="text-muted">${formatFileSize(file.size)}</small>
                    </div>
                    <div class="mt-2">
                        <input type="text" class="form-control form-control-sm" 
                               name="image_descriptions[${fileId}]"
                               placeholder="Description (optional)" 
                               onchange="updateFileDescription('${fileId}', this.value)">
                    </div>
                    <div class="mt-2 text-center">
                        <button type="button" class="btn btn-sm btn-outline-danger" 
                                onclick="removeFilePreview('${fileId}')">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        `;

        previewContainer.appendChild(col);
    }

    // Update preview area visibility
    function updatePreviewArea() {
        if (selectedFiles.length > 0) {
            previewArea.style.display = 'block';
        } else {
            previewArea.style.display = 'none';
        }
    }

    // Format file size
    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }

    // Global functions
    window.removeFilePreview = function(fileId) {
        const element = document.querySelector(`[data-file-id="${fileId}"]`);
        if (element) {
            const fileName = element.querySelector('.file-info small').textContent;
            selectedFiles = selectedFiles.filter(file => file.name !== fileName);
            element.remove();
            updatePreviewArea();
            updateFileInput();
        }
    };

    window.updateFileDescription = function(fileId, description) {
        // This could be used to store descriptions with files
        console.log(`File ${fileId} description: ${description}`);
    };

    window.removeExistingImage = function(imageId) {
        if (confirm('Are you sure you want to remove this image?')) {
            // Add logic to mark image for deletion
            const imageElement = event.target.closest('.col-md-3');
            imageElement.style.opacity = '0.5';
            
            // Create hidden input to mark for deletion
            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'delete_images[]';
            hiddenInput.value = imageId;
            document.querySelector('form').appendChild(hiddenInput);
            
            // Disable the remove button
            event.target.disabled = true;
            event.target.innerHTML = '<i class="fas fa-check"></i>';
        }
    };
});
</script>

<style>
.drop-zone {
    border: 2px dashed #ddd;
    border-radius: 10px;
    padding: 40px;
    text-align: center;
    transition: all 0.3s ease;
    background-color: #fafafa;
    cursor: pointer;
}

.drop-zone:hover,
.drop-zone.drag-over {
    border-color: #007bff;
    background-color: #f0f8ff;
}

.upload-info {
    background-color: #f8f9fa;
    padding: 20px;
    border-radius: 10px;
    height: 100%;
}

.upload-info ul li {
    padding: 5px 0;
}

.file-preview-container {
    max-height: 400px;
    overflow-y: auto;
}

.existing-images .card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
}

@media (max-width: 768px) {
    .drop-zone {
        padding: 20px;
    }
    
    .drop-zone-content h5 {
        font-size: 1.1rem;
    }
    
    .drop-zone-content p {
        font-size: 0.9rem;
    }
}
</style>