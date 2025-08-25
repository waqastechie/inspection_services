<!-- Equipment Assignment Modal -->
<div class="modal fade" id="equipmentModal" tabindex="-1" aria-labelledby="equipmentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="equipmentModalLabel">
                    <i class="fas fa-tools me-2"></i>
                    Add Equipment Details
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="equipmentForm">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="equipmentType" class="form-label">Equipment Type *</label>
                            <select class="form-select" id="equipmentTypeModal" required>
                                <option value="">Select equipment type</option>
                                <option value="Mobile Crane">Mobile Crane</option>
                                <option value="Tower Crane">Tower Crane</option>
                                <option value="Overhead Crane">Overhead Crane</option>
                                <option value="Jib Crane">Jib Crane</option>
                                <option value="Gantry Crane">Gantry Crane</option>
                                <option value="Hoist">Hoist</option>
                                <option value="Winch">Winch</option>
                                <option value="Forklift">Forklift</option>
                                <option value="Lifting Sling">Lifting Sling</option>
                                <option value="Chain Block">Chain Block</option>
                                <option value="Wire Rope">Wire Rope</option>
                                <option value="Shackle">Shackle</option>
                                <option value="Hook">Hook</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="equipmentModelSerial" class="form-label">Model/Serial Number *</label>
                            <input type="text" class="form-control" id="equipmentModelSerial" required 
                                   placeholder="Model and serial number">
                        </div>
                        <div class="col-md-6">
                            <label for="equipmentCapacity" class="form-label">Capacity</label>
                            <input type="text" class="form-control" id="equipmentCapacity" 
                                   placeholder="e.g., 50 tonnes, 5000 kg">
                        </div>
                        <div class="col-md-6">
                            <label for="equipmentManufacturer" class="form-label">Manufacturer</label>
                            <input type="text" class="form-control" id="equipmentManufacturer" 
                                   placeholder="Equipment manufacturer">
                        </div>
                        <div class="col-md-6">
                            <label for="equipmentCertDate" class="form-label">Last Certification Date</label>
                            <input type="date" class="form-control" id="equipmentCertDate">
                        </div>
                        <div class="col-md-6">
                            <label for="equipmentNextDue" class="form-label">Next Inspection Due</label>
                            <input type="date" class="form-control" id="equipmentNextDue">
                        </div>
                        <div class="col-md-6">
                            <label for="equipmentStatus" class="form-label">Status</label>
                            <select class="form-select" id="equipmentStatus">
                                <option value="Good">Good</option>
                                <option value="Satisfactory">Satisfactory</option>
                                <option value="Needs Attention">Needs Attention</option>
                                <option value="Out of Service">Out of Service</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="equipmentCertNumber" class="form-label">Certificate Number</label>
                            <input type="text" class="form-control" id="equipmentCertNumber" 
                                   placeholder="Certificate reference number">
                        </div>
                        <div class="col-12">
                            <label for="equipmentComments" class="form-label">Comments</label>
                            <textarea class="form-control" id="equipmentComments" rows="2" 
                                      placeholder="Any additional comments about this equipment"></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="addEquipment()">
                    <i class="fas fa-plus me-2"></i>Add Equipment
                </button>
            </div>
        </div>
    </div>
</div>
