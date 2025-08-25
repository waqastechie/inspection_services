<!-- Inspection Checklist Section -->
<section class="form-section" id="section-checklist">
    <div class="section-header">
        <h2 class="section-title">
            <i class="fas fa-clipboard-check"></i>
            Inspection Checklist
        </h2>
        <p class="section-description">
            Comprehensive inspection checklist covering all critical aspects of lifting equipment and operations.
        </p>
    </div>

    <div class="section-content">
        <!-- General Equipment Inspection -->
        <div class="checklist-category mb-4">
            <h4 class="checklist-title">
                <i class="fas fa-cogs me-2"></i>General Equipment Condition
            </h4>
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="overall_condition" id="overallGood" value="Good">
                        <label class="form-check-label text-success" for="overallGood">
                            <i class="fas fa-check-circle me-2"></i>Good - No defects observed
                        </label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="overall_condition" id="overallSatisfactory" value="Satisfactory">
                        <label class="form-check-label text-warning" for="overallSatisfactory">
                            <i class="fas fa-exclamation-triangle me-2"></i>Satisfactory - Minor issues noted
                        </label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="overall_condition" id="overallUnsatisfactory" value="Unsatisfactory">
                        <label class="form-check-label text-danger" for="overallUnsatisfactory">
                            <i class="fas fa-times-circle me-2"></i>Unsatisfactory - Defects require attention
                        </label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="overall_condition" id="overallDangerous" value="Dangerous">
                        <label class="form-check-label text-danger" for="overallDangerous">
                            <i class="fas fa-ban me-2"></i>Dangerous - Equipment unsafe for use
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <!-- Structural Inspection -->
        <div class="checklist-category mb-4">
            <h4 class="checklist-title">
                <i class="fas fa-building me-2"></i>Structural Components
            </h4>
            <div class="table-responsive">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>Component</th>
                            <th class="text-center">Pass</th>
                            <th class="text-center">Fail</th>
                            <th class="text-center">N/A</th>
                            <th>Comments</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Load Block Assembly</td>
                            <td class="text-center">
                                <input class="form-check-input" type="radio" name="load_block" value="Pass">
                            </td>
                            <td class="text-center">
                                <input class="form-check-input" type="radio" name="load_block" value="Fail">
                            </td>
                            <td class="text-center">
                                <input class="form-check-input" type="radio" name="load_block" value="N/A">
                            </td>
                            <td>
                                <input type="text" class="form-control form-control-sm" name="load_block_comments" placeholder="Comments">
                            </td>
                        </tr>
                        <tr>
                            <td>Hook Condition</td>
                            <td class="text-center">
                                <input class="form-check-input" type="radio" name="hook_condition" value="Pass">
                            </td>
                            <td class="text-center">
                                <input class="form-check-input" type="radio" name="hook_condition" value="Fail">
                            </td>
                            <td class="text-center">
                                <input class="form-check-input" type="radio" name="hook_condition" value="N/A">
                            </td>
                            <td>
                                <input type="text" class="form-control form-control-sm" name="hook_comments" placeholder="Comments">
                            </td>
                        </tr>
                        <tr>
                            <td>Wire Rope/Chains</td>
                            <td class="text-center">
                                <input class="form-check-input" type="radio" name="wire_rope" value="Pass">
                            </td>
                            <td class="text-center">
                                <input class="form-check-input" type="radio" name="wire_rope" value="Fail">
                            </td>
                            <td class="text-center">
                                <input class="form-check-input" type="radio" name="wire_rope" value="N/A">
                            </td>
                            <td>
                                <input type="text" class="form-control form-control-sm" name="wire_rope_comments" placeholder="Comments">
                            </td>
                        </tr>
                        <tr>
                            <td>Boom/Jib Structure</td>
                            <td class="text-center">
                                <input class="form-check-input" type="radio" name="boom_structure" value="Pass">
                            </td>
                            <td class="text-center">
                                <input class="form-check-input" type="radio" name="boom_structure" value="Fail">
                            </td>
                            <td class="text-center">
                                <input class="form-check-input" type="radio" name="boom_structure" value="N/A">
                            </td>
                            <td>
                                <input type="text" class="form-control form-control-sm" name="boom_comments" placeholder="Comments">
                            </td>
                        </tr>
                        <tr>
                            <td>Outriggers/Stabilizers</td>
                            <td class="text-center">
                                <input class="form-check-input" type="radio" name="outriggers" value="Pass">
                            </td>
                            <td class="text-center">
                                <input class="form-check-input" type="radio" name="outriggers" value="Fail">
                            </td>
                            <td class="text-center">
                                <input class="form-check-input" type="radio" name="outriggers" value="N/A">
                            </td>
                            <td>
                                <input type="text" class="form-control form-control-sm" name="outriggers_comments" placeholder="Comments">
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Safety Systems -->
        <div class="checklist-category mb-4">
            <h4 class="checklist-title">
                <i class="fas fa-shield-alt me-2"></i>Safety Systems
            </h4>
            <div class="table-responsive">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>Safety Feature</th>
                            <th class="text-center">Pass</th>
                            <th class="text-center">Fail</th>
                            <th class="text-center">N/A</th>
                            <th>Comments</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Load Moment Indicator (LMI)</td>
                            <td class="text-center">
                                <input class="form-check-input" type="radio" name="lmi_system" value="Pass">
                            </td>
                            <td class="text-center">
                                <input class="form-check-input" type="radio" name="lmi_system" value="Fail">
                            </td>
                            <td class="text-center">
                                <input class="form-check-input" type="radio" name="lmi_system" value="N/A">
                            </td>
                            <td>
                                <input type="text" class="form-control form-control-sm" name="lmi_comments" placeholder="Comments">
                            </td>
                        </tr>
                        <tr>
                            <td>Anti-Two Block System</td>
                            <td class="text-center">
                                <input class="form-check-input" type="radio" name="anti_two_block" value="Pass">
                            </td>
                            <td class="text-center">
                                <input class="form-check-input" type="radio" name="anti_two_block" value="Fail">
                            </td>
                            <td class="text-center">
                                <input class="form-check-input" type="radio" name="anti_two_block" value="N/A">
                            </td>
                            <td>
                                <input type="text" class="form-control form-control-sm" name="anti_two_block_comments" placeholder="Comments">
                            </td>
                        </tr>
                        <tr>
                            <td>Emergency Stop Systems</td>
                            <td class="text-center">
                                <input class="form-check-input" type="radio" name="emergency_stop" value="Pass">
                            </td>
                            <td class="text-center">
                                <input class="form-check-input" type="radio" name="emergency_stop" value="Fail">
                            </td>
                            <td class="text-center">
                                <input class="form-check-input" type="radio" name="emergency_stop" value="N/A">
                            </td>
                            <td>
                                <input type="text" class="form-control form-control-sm" name="emergency_stop_comments" placeholder="Comments">
                            </td>
                        </tr>
                        <tr>
                            <td>Warning Devices/Alarms</td>
                            <td class="text-center">
                                <input class="form-check-input" type="radio" name="warning_devices" value="Pass">
                            </td>
                            <td class="text-center">
                                <input class="form-check-input" type="radio" name="warning_devices" value="Fail">
                            </td>
                            <td class="text-center">
                                <input class="form-check-input" type="radio" name="warning_devices" value="N/A">
                            </td>
                            <td>
                                <input type="text" class="form-control form-control-sm" name="warning_devices_comments" placeholder="Comments">
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Additional Checks -->
        <div class="checklist-category">
            <h4 class="checklist-title">
                <i class="fas fa-clipboard-list me-2"></i>Additional Verification
            </h4>
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="documentation_complete" id="docComplete" value="1">
                        <label class="form-check-label" for="docComplete">
                            All required documentation is complete and current
                        </label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="operator_competent" id="operatorComp" value="1">
                        <label class="form-check-label" for="operatorComp">
                            Operator demonstrated competency
                        </label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="site_conditions_safe" id="siteSafe" value="1">
                        <label class="form-check-label" for="siteSafe">
                            Site conditions are safe for operation
                        </label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="emergency_procedures" id="emergencyProc" value="1">
                        <label class="form-check-label" for="emergencyProc">
                            Emergency procedures are in place
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
