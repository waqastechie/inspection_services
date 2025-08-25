<!-- Image Upload Section -->
<section class="form-section" id="section-images">
    <div class="section-header">
        <h2 class="section-title">
            <i class="fas fa-camera"></i>
            Inspection Images
        </h2>
        <p class="section-description">
            Upload inspection photos and documentation images
        </p>
    </div>

    <div class="section-content">
        <!-- Upload Area -->
        <div class="row g-4">
            <div class="col-12">
                <div class="upload-container">
                    <div class="upload-area" id="uploadArea">
                        <div class="upload-content">
                            <i class="fas fa-cloud-upload-alt upload-icon"></i>
                            <h5 class="upload-title">Drop images here or click to upload</h5>
                            <p class="upload-subtitle">Support: JPG, JPEG, PNG, GIF (Max 10MB per file)</p>
                            <button type="button" class="btn btn-primary" id="selectFilesBtn">
                                <i class="fas fa-plus me-2"></i>Select Files
                            </button>
                            <input type="file" id="imageUpload" name="inspection_images[]" multiple accept="image/*" style="display: none;">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Upload Progress -->
            <div class="col-12" id="uploadProgress" style="display: none;">
                <div class="card">
                    <div class="card-body">
                        <h6 class="card-title">
                            <i class="fas fa-upload me-2"></i>Uploading Images...
                        </h6>
                        <div class="progress">
                            <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 0%" id="progressBar"></div>
                        </div>
                        <small class="text-muted mt-2" id="uploadStatus">Preparing upload...</small>
                    </div>
                </div>
            </div>

            <!-- No Images Message -->
            <div class="col-12" id="noImagesMessage">
                <div class="text-center py-4">
                    <i class="fas fa-images text-muted" style="font-size: 3rem; opacity: 0.3;"></i>
                    <h6 class="text-muted mt-3">No images uploaded yet</h6>
                    <p class="text-muted">Upload inspection photos to document your findings</p>
                </div>
            </div>

            <!-- Images Preview Area -->
            <div class="col-12" id="imagesPreviewArea" style="display: none;">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="mb-0">
                        <i class="fas fa-images me-2 text-primary"></i>
                        Uploaded Images (<span id="imageCount">0</span>)
                    </h6>
                    <div>
                        <button type="button" class="btn btn-outline-primary btn-sm" id="addMoreImagesBtn">
                            <i class="fas fa-plus me-2"></i>Add More
                        </button>
                        <button type="button" class="btn btn-outline-danger btn-sm" id="clearAllImagesBtn">
                            <i class="fas fa-trash me-2"></i>Clear All
                        </button>
                    </div>
                </div>
                
                <div class="row g-3" id="imagePreviewContainer">
                    <!-- Image previews will be added here -->
                </div>
            </div>
        </div>

        <!-- Hidden inputs for form submission -->
        <div id="imageInputsContainer">
            <!-- Hidden inputs will be generated here -->
        </div>
    </div>
</section>

<style>
.upload-container {
    border: 2px dashed #dee2e6;
    border-radius: 0.5rem;
    transition: all 0.3s ease;
    background-color: #f8f9fa;
}

.upload-container:hover {
    border-color: #0d6efd;
    background-color: #e7f1ff;
}

.upload-area {
    padding: 3rem 2rem;
    text-align: center;
    cursor: pointer;
}

.upload-area.dragover {
    border-color: #0d6efd;
    background-color: #e7f1ff;
}

.upload-icon {
    font-size: 3rem;
    color: #6c757d;
    margin-bottom: 1rem;
}

.upload-title {
    color: #495057;
    margin-bottom: 0.5rem;
}

.upload-subtitle {
    color: #6c757d;
    margin-bottom: 1.5rem;
}

.image-preview-card {
    position: relative;
    border: 1px solid #dee2e6;
    border-radius: 0.5rem;
    overflow: hidden;
    background: white;
    transition: all 0.3s ease;
}

.image-preview-card:hover {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    transform: translateY(-2px);
}

.image-container {
    position: relative;
    overflow: hidden;
}

.image-preview {
    width: 100%;
    height: 200px;
    object-fit: cover;
    cursor: pointer;
}

.image-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.7);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.3s ease;
    pointer-events: none;
}

.image-container:hover .image-overlay {
    opacity: 1;
    pointer-events: auto;
}

/* Hide overlay when caption is focused */
.image-preview-card.caption-focused .image-overlay {
    opacity: 0 !important;
    pointer-events: none !important;
}

.image-controls {
    display: flex;
    gap: 0.5rem;
}

.image-info {
    padding: 0.75rem;
    background: white;
}

.image-name {
    font-size: 0.875rem;
    font-weight: 500;
    margin-bottom: 0.25rem;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.image-size {
    font-size: 0.75rem;
    color: #6c757d;
}

.caption-container {
    background: #f8f9fa;
    border-top: 1px solid #dee2e6;
}

.image-caption {
    background: #f8f9fa;
    border: none;
    font-size: 0.875rem;
    resize: vertical;
    transition: all 0.3s ease;
}

.image-caption:focus {
    background: white;
    box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
    border-color: #86b7fe;
}

.remove-image {
    position: absolute;
    top: 0.5rem;
    right: 0.5rem;
    width: 2rem;
    height: 2rem;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 10;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.image-container:hover .remove-image {
    opacity: 1;
}
</style>

<script>
// Image upload management
let uploadedImages = [];
let imageCounter = 1;

document.addEventListener('DOMContentLoaded', function() {
    const uploadArea = document.getElementById('uploadArea');
    const imageUpload = document.getElementById('imageUpload');
    const selectFilesBtn = document.getElementById('selectFilesBtn');
    const addMoreImagesBtn = document.getElementById('addMoreImagesBtn');
    const clearAllImagesBtn = document.getElementById('clearAllImagesBtn');

    // Click handlers
    selectFilesBtn.addEventListener('click', () => imageUpload.click());
    addMoreImagesBtn.addEventListener('click', () => imageUpload.click());
    uploadArea.addEventListener('click', () => imageUpload.click());

    // File input change handler
    imageUpload.addEventListener('change', handleFileSelect);

    // Drag and drop handlers
    uploadArea.addEventListener('dragover', handleDragOver);
    uploadArea.addEventListener('dragleave', handleDragLeave);
    uploadArea.addEventListener('drop', handleDrop);

    // Clear all images
    clearAllImagesBtn.addEventListener('click', function() {
        if (confirm('Are you sure you want to remove all images?')) {
            uploadedImages = [];
            updateImageDisplay();
            updateImageInputs();
        }
    });
});

function handleDragOver(e) {
    e.preventDefault();
    e.stopPropagation();
    e.currentTarget.classList.add('dragover');
}

function handleDragLeave(e) {
    e.preventDefault();
    e.stopPropagation();
    e.currentTarget.classList.remove('dragover');
}

function handleDrop(e) {
    e.preventDefault();
    e.stopPropagation();
    e.currentTarget.classList.remove('dragover');
    
    const files = Array.from(e.dataTransfer.files);
    processFiles(files);
}

function handleFileSelect(e) {
    const files = Array.from(e.target.files);
    processFiles(files);
    // Reset input so same file can be selected again
    e.target.value = '';
}

function processFiles(files) {
    const validFiles = files.filter(file => {
        // Check file type
        if (!file.type.startsWith('image/')) {
            showToast(`${file.name} is not a valid image file`, 'warning');
            return false;
        }
        
        // Check file size (10MB limit)
        if (file.size > 10 * 1024 * 1024) {
            showToast(`${file.name} is too large. Maximum size is 10MB`, 'warning');
            return false;
        }
        
        return true;
    });

    if (validFiles.length === 0) return;

    // Show upload progress
    showUploadProgress();

    // Process each file
    validFiles.forEach((file, index) => {
        const reader = new FileReader();
        reader.onload = function(e) {
            const imageData = {
                id: imageCounter++,
                file: file,
                name: file.name,
                size: formatFileSize(file.size),
                dataUrl: e.target.result,
                caption: ''
            };

            uploadedImages.push(imageData);

            // Update progress
            const progress = ((index + 1) / validFiles.length) * 100;
            updateUploadProgress(progress, `Processing ${file.name}...`);

            // If this is the last file, update the display
            if (index === validFiles.length - 1) {
                setTimeout(() => {
                    hideUploadProgress();
                    updateImageDisplay();
                    updateImageInputs();
                    showToast(`${validFiles.length} image(s) uploaded successfully`, 'success');
                }, 500);
            }
        };
        reader.readAsDataURL(file);
    });
}

function showUploadProgress() {
    document.getElementById('uploadProgress').style.display = 'block';
    updateUploadProgress(0, 'Preparing upload...');
}

function updateUploadProgress(percentage, status) {
    const progressBar = document.getElementById('progressBar');
    const uploadStatus = document.getElementById('uploadStatus');
    
    progressBar.style.width = percentage + '%';
    progressBar.textContent = Math.round(percentage) + '%';
    uploadStatus.textContent = status;
}

function hideUploadProgress() {
    document.getElementById('uploadProgress').style.display = 'none';
}

function updateImageDisplay() {
    const noImagesMessage = document.getElementById('noImagesMessage');
    const imagesPreviewArea = document.getElementById('imagesPreviewArea');
    const imagePreviewContainer = document.getElementById('imagePreviewContainer');
    const imageCount = document.getElementById('imageCount');

    if (uploadedImages.length === 0) {
        noImagesMessage.style.display = 'block';
        imagesPreviewArea.style.display = 'none';
        return;
    }

    noImagesMessage.style.display = 'none';
    imagesPreviewArea.style.display = 'block';
    imageCount.textContent = uploadedImages.length;

    // Clear existing previews
    imagePreviewContainer.innerHTML = '';

    // Generate image preview cards
    uploadedImages.forEach(image => {
        const col = document.createElement('div');
        col.className = 'col-md-6 col-lg-4';
        
        col.innerHTML = `
            <div class="image-preview-card">
                <div class="image-container position-relative">
                    <button type="button" class="btn btn-danger btn-sm remove-image" 
                            onclick="removeImage(${image.id})" title="Remove Image">
                        <i class="fas fa-times"></i>
                    </button>
                    
                    <img src="${image.dataUrl}" alt="${image.name}" class="image-preview" 
                         onclick="previewImage('${image.dataUrl}', '${image.name}')">
                    
                    <div class="image-overlay">
                        <div class="image-controls">
                            <button type="button" class="btn btn-light btn-sm" 
                                    onclick="previewImage('${image.dataUrl}', '${image.name}')" title="View Full Size">
                                <i class="fas fa-search-plus"></i>
                            </button>
                            <button type="button" class="btn btn-danger btn-sm" 
                                    onclick="removeImage(${image.id})" title="Remove">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
                
                <div class="image-info">
                    <div class="image-name" title="${image.name}">${image.name}</div>
                    <div class="image-size">${image.size}</div>
                </div>
                
                <div class="caption-container">
                    <textarea class="form-control image-caption" placeholder="Add caption or description..." 
                              onchange="updateImageCaption(${image.id}, this.value)" 
                              onfocus="this.parentElement.parentElement.classList.add('caption-focused')"
                              onblur="this.parentElement.parentElement.classList.remove('caption-focused')"
                              rows="2">${image.caption}</textarea>
                </div>
            </div>
        `;
        
        imagePreviewContainer.appendChild(col);
    });
}

function removeImage(imageId) {
    if (confirm('Are you sure you want to remove this image?')) {
        uploadedImages = uploadedImages.filter(img => img.id !== imageId);
        updateImageDisplay();
        updateImageInputs();
        showToast('Image removed successfully', 'success');
    }
}

function updateImageCaption(imageId, caption) {
    const image = uploadedImages.find(img => img.id === imageId);
    if (image) {
        image.caption = caption;
        updateImageInputs();
    }
}

function previewImage(dataUrl, fileName) {
    // Create modal for full-size image preview
    const modal = document.createElement('div');
    modal.className = 'modal fade';
    modal.innerHTML = `
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">${fileName}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                    <img src="${dataUrl}" alt="${fileName}" class="img-fluid" style="max-height: 70vh;">
                </div>
            </div>
        </div>
    `;
    
    document.body.appendChild(modal);
    const bsModal = new bootstrap.Modal(modal);
    bsModal.show();
    
    // Remove modal from DOM when hidden
    modal.addEventListener('hidden.bs.modal', () => {
        modal.remove();
    });
}

function updateImageInputs() {
    const container = document.getElementById('imageInputsContainer');
    container.innerHTML = '';

    uploadedImages.forEach((image, index) => {
        // Create hidden input for each image with metadata
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = `inspection_images[${index}]`;
        input.value = JSON.stringify({
            name: image.name,
            size: image.size,
            dataUrl: image.dataUrl,
            caption: image.caption,
            uploadedAt: new Date().toISOString()
        });
        container.appendChild(input);
    });
}

function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}

// Toast notification function
function showToast(message, type = 'info') {
    let toastContainer = document.querySelector('.toast-container');
    if (!toastContainer) {
        toastContainer = document.createElement('div');
        toastContainer.className = 'toast-container position-fixed top-0 end-0 p-3';
        document.body.appendChild(toastContainer);
    }

    const toastEl = document.createElement('div');
    toastEl.className = `toast align-items-center text-white bg-${type === 'warning' ? 'warning' : type === 'success' ? 'success' : 'primary'} border-0`;
    toastEl.innerHTML = `
        <div class="d-flex">
            <div class="toast-body">${message}</div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    `;

    toastContainer.appendChild(toastEl);
    const toast = new bootstrap.Toast(toastEl);
    toast.show();

    toastEl.addEventListener('hidden.bs.toast', () => {
        toastEl.remove();
    });
}

// Make functions available globally
window.removeImage = removeImage;
window.updateImageCaption = updateImageCaption;
window.previewImage = previewImage;
window.populateImages = populateImages;

// Populate images on edit mode
function populateImages(imageData) {
    if (!Array.isArray(imageData)) return;
    
    uploadedImages = imageData.map(img => ({
        id: imageCounter++,
        name: img.name || 'Unknown',
        size: img.size || '0 Bytes',
        dataUrl: img.dataUrl || '',
        caption: img.caption || ''
    }));
    
    updateImageDisplay();
    updateImageInputs();
}
</script>
