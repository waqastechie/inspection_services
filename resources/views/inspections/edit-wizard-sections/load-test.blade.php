<!-- Load Test Section -->
<div class="form-section" id="section-load-test">
    <div class="section-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="section-title">
                    <i class="fas fa-dumbbell me-3"></i>
                    Load Test
                </h2>
                <p class="section-description">
                    Load testing examination details and requirements
                </p>
            </div>
            <div class="section-indicator">
                <i class="fas fa-circle text-muted"></i>
            </div>
        </div>
    </div>

    <div class="section-content">
        <!-- Inspector Assignment -->
        <div class="inspection-question mb-4">
             <label class="form-label fw-bold">
                 <i class="fas fa-user-check me-2"></i>Assigned Load Test Inspector
             </label>
             <select class="form-control" name="load_test_inspector">
                 <option value="">Select Inspector</option>
                 @if(isset($personnel))
                     @foreach($personnel as $person)
                         <option value="{{ $person->id }}"
                             {{ (isset($inspection) && $inspection?->loadTest) ? (old('load_test_inspector', $inspection->loadTest->load_test_inspector) == $person->id ? 'selected' : '') : (old('load_test_inspector') == $person->id ? 'selected' : '') }}>
                             {{ $person->first_name }} {{ $person->last_name }} - {{ $person->job_title }}
                         </option>
                     @endforeach
                 @endif
             </select>
         </div>

        <div class="card shadow-sm border-0">
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-sliders-h me-2 text-primary"></i>Test Parameters
                </h5>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-sm btn-outline-primary load-test-template" data-load-test-template="pass">
                        <i class="fas fa-check me-1"></i>Fill Pass Template
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-danger load-test-template" data-load-test-template="fail">
                        <i class="fas fa-times me-1"></i>Fill Fail Template
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered align-middle mb-0">
                        <thead class="table-light text-center">
                            <tr>
                                <th class="w-15">Duration Held</th>
                                <th class="w-15">Two Points Diagonal @</th>
                                <th class="w-15">4 Points @</th>
                                <th class="w-10">Deflection</th>
                                <th class="w-10">Deformation</th>
                                <th class="w-15">Distance From Ground</th>
                                <th class="w-10">Result</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="text-center">
                                <td>
                                    <select class="form-select" name="load_test_duration">
                                        <option value="">Select</option>
                                        <option value="5 Minutes" {{ (isset($inspection) && $inspection?->loadTest) ? (old('load_test_duration', $inspection->loadTest->load_test_duration) == '5 Minutes' ? 'selected' : '') : (old('load_test_duration') == '5 Minutes' ? 'selected' : '') }}>5 Minutes</option>
                                        <option value="10 Minutes" {{ (isset($inspection) && $inspection?->loadTest) ? (old('load_test_duration', $inspection->loadTest->load_test_duration) == '10 Minutes' ? 'selected' : '') : (old('load_test_duration') == '10 Minutes' ? 'selected' : '') }}>10 Minutes</option>
                                        <option value="15 Minutes" {{ (isset($inspection) && $inspection?->loadTest) ? (old('load_test_duration', $inspection->loadTest->load_test_duration) == '15 Minutes' ? 'selected' : '') : (old('load_test_duration') == '15 Minutes' ? 'selected' : '') }}>15 Minutes</option>
                                        <option value="20 Minutes" {{ (isset($inspection) && $inspection?->loadTest) ? (old('load_test_duration', $inspection->loadTest->load_test_duration) == '20 Minutes' ? 'selected' : '') : (old('load_test_duration') == '20 Minutes' ? 'selected' : '') }}>20 Minutes</option>
                                        <option value="30 Minutes" {{ (isset($inspection) && $inspection?->loadTest) ? (old('load_test_duration', $inspection->loadTest->load_test_duration) == '30 Minutes' ? 'selected' : '') : (old('load_test_duration') == '30 Minutes' ? 'selected' : '') }}>30 Minutes</option>
                                    </select>
                                </td>
                                <td>
                                    <select class="form-select" name="load_test_two_points_diagonal">
                                        <option value="">Select</option>
                                        <option value="30 Degree" {{ (isset($inspection) && $inspection?->loadTest) ? (old('load_test_two_points_diagonal', $inspection->loadTest->load_test_two_points_diagonal) == '30 Degree' ? 'selected' : '') : (old('load_test_two_points_diagonal') == '30 Degree' ? 'selected' : '') }}>30 Degree</option>
                                        <option value="45 Degree" {{ (isset($inspection) && $inspection?->loadTest) ? (old('load_test_two_points_diagonal', $inspection->loadTest->load_test_two_points_diagonal) == '45 Degree' ? 'selected' : '') : (old('load_test_two_points_diagonal') == '45 Degree' ? 'selected' : '') }}>45 Degree</option>
                                        <option value="60 Degree" {{ (isset($inspection) && $inspection?->loadTest) ? (old('load_test_two_points_diagonal', $inspection->loadTest->load_test_two_points_diagonal) == '60 Degree' ? 'selected' : '') : (old('load_test_two_points_diagonal') == '60 Degree' ? 'selected' : '') }}>60 Degree</option>
                                        <option value="90 Degree" {{ (isset($inspection) && $inspection?->loadTest) ? (old('load_test_two_points_diagonal', $inspection->loadTest->load_test_two_points_diagonal) == '90 Degree' ? 'selected' : '') : (old('load_test_two_points_diagonal') == '90 Degree' ? 'selected' : '') }}>90 Degree</option>
                                    </select>
                                </td>
                                <td>
                                    <select class="form-select" name="load_test_four_points">
                                        <option value="">Select</option>
                                        <option value="30 Degree" {{ (isset($inspection) && $inspection?->loadTest) ? (old('load_test_four_points', $inspection->loadTest->load_test_four_points) == '30 Degree' ? 'selected' : '') : (old('load_test_four_points') == '30 Degree' ? 'selected' : '') }}>30 Degree</option>
                                        <option value="45 Degree" {{ (isset($inspection) && $inspection?->loadTest) ? (old('load_test_four_points', $inspection->loadTest->load_test_four_points) == '45 Degree' ? 'selected' : '') : (old('load_test_four_points') == '45 Degree' ? 'selected' : '') }}>45 Degree</option>
                                        <option value="60 Degree" {{ (isset($inspection) && $inspection?->loadTest) ? (old('load_test_four_points', $inspection->loadTest->load_test_four_points) == '60 Degree' ? 'selected' : '') : (old('load_test_four_points') == '60 Degree' ? 'selected' : '') }}>60 Degree</option>
                                        <option value="90 Degree" {{ (isset($inspection) && $inspection?->loadTest) ? (old('load_test_four_points', $inspection->loadTest->load_test_four_points) == '90 Degree' ? 'selected' : '') : (old('load_test_four_points') == '90 Degree' ? 'selected' : '') }}>90 Degree</option>
                                    </select>
                                </td>
                                <td>
                                    <select class="form-select" name="load_test_deflection">
                                        <option value="">Select</option>
                                        <option value="NA" {{ (isset($inspection) && $inspection?->loadTest) ? (old('load_test_deflection', $inspection->loadTest->load_test_deflection) == 'NA' ? 'selected' : '') : (old('load_test_deflection') == 'NA' ? 'selected' : '') }}>NA</option>
                                        <option value="1/500" {{ (isset($inspection) && $inspection?->loadTest) ? (old('load_test_deflection', $inspection->loadTest->load_test_deflection) == '1/500' ? 'selected' : '') : (old('load_test_deflection') == '1/500' ? 'selected' : '') }}>1/500</option>
                                        <option value="1/400" {{ (isset($inspection) && $inspection?->loadTest) ? (old('load_test_deflection', $inspection->loadTest->load_test_deflection) == '1/400' ? 'selected' : '') : (old('load_test_deflection') == '1/400' ? 'selected' : '') }}>1/400</option>
                                        <option value="1/300" {{ (isset($inspection) && $inspection?->loadTest) ? (old('load_test_deflection', $inspection->loadTest->load_test_deflection) == '1/300' ? 'selected' : '') : (old('load_test_deflection') == '1/300' ? 'selected' : '') }}>1/300</option>
                                        <option value="1/250" {{ (isset($inspection) && $inspection?->loadTest) ? (old('load_test_deflection', $inspection->loadTest->load_test_deflection) == '1/250' ? 'selected' : '') : (old('load_test_deflection') == '1/250' ? 'selected' : '') }}>1/250</option>
                                        <option value="1/200" {{ (isset($inspection) && $inspection?->loadTest) ? (old('load_test_deflection', $inspection->loadTest->load_test_deflection) == '1/200' ? 'selected' : '') : (old('load_test_deflection') == '1/200' ? 'selected' : '') }}>1/200</option>
                                        <option value="1/150" {{ (isset($inspection) && $inspection?->loadTest) ? (old('load_test_deflection', $inspection->loadTest->load_test_deflection) == '1/150' ? 'selected' : '') : (old('load_test_deflection') == '1/150' ? 'selected' : '') }}>1/150</option>
                                    </select>
                                </td>
                                <td>
                                    <select class="form-select" name="load_test_deformation">
                                        <option value="">Select</option>
                                        <option value="NA" {{ (isset($inspection) && $inspection?->loadTest) ? (old('load_test_deformation', $inspection->loadTest->load_test_deformation) == 'NA' ? 'selected' : '') : (old('load_test_deformation') == 'NA' ? 'selected' : '') }}>NA</option>
                                        <option value="0%" {{ (isset($inspection) && $inspection?->loadTest) ? (old('load_test_deformation', $inspection->loadTest->load_test_deformation) == '0%' ? 'selected' : '') : (old('load_test_deformation') == '0%' ? 'selected' : '') }}>0%</option>
                                        <option value="1%" {{ (isset($inspection) && $inspection?->loadTest) ? (old('load_test_deformation', $inspection->loadTest->load_test_deformation) == '1%' ? 'selected' : '') : (old('load_test_deformation') == '1%' ? 'selected' : '') }}>1%</option>
                                        <option value="2%" {{ (isset($inspection) && $inspection?->loadTest) ? (old('load_test_deformation', $inspection->loadTest->load_test_deformation) == '2%' ? 'selected' : '') : (old('load_test_deformation') == '2%' ? 'selected' : '') }}>2%</option>
                                        <option value="5%" {{ (isset($inspection) && $inspection?->loadTest) ? (old('load_test_deformation', $inspection->loadTest->load_test_deformation) == '5%' ? 'selected' : '') : (old('load_test_deformation') == '5%' ? 'selected' : '') }}>5%</option>
                                    </select>
                                </td>
                                <td>
                                    <select class="form-select" name="load_test_distance_from_ground">
                                        <option value="">Select</option>
                                        <option value="50mm" {{ (isset($inspection) && $inspection?->loadTest) ? (old('load_test_distance_from_ground', $inspection->loadTest->load_test_distance_from_ground) == '50mm' ? 'selected' : '') : (old('load_test_distance_from_ground') == '50mm' ? 'selected' : '') }}>50mm</option>
                                        <option value="100mm" {{ (isset($inspection) && $inspection?->loadTest) ? (old('load_test_distance_from_ground', $inspection->loadTest->load_test_distance_from_ground) == '100mm' ? 'selected' : '') : (old('load_test_distance_from_ground') == '100mm' ? 'selected' : '') }}>100mm</option>
                                        <option value="150mm" {{ (isset($inspection) && $inspection?->loadTest) ? (old('load_test_distance_from_ground', $inspection->loadTest->load_test_distance_from_ground) == '150mm' ? 'selected' : '') : (old('load_test_distance_from_ground') == '150mm' ? 'selected' : '') }}>150mm</option>
                                        <option value="200mm" {{ (isset($inspection) && $inspection?->loadTest) ? (old('load_test_distance_from_ground', $inspection->loadTest->load_test_distance_from_ground) == '200mm' ? 'selected' : '') : (old('load_test_distance_from_ground') == '200mm' ? 'selected' : '') }}>200mm</option>
                                        <option value="250mm" {{ (isset($inspection) && $inspection?->loadTest) ? (old('load_test_distance_from_ground', $inspection->loadTest->load_test_distance_from_ground) == '250mm' ? 'selected' : '') : (old('load_test_distance_from_ground') == '250mm' ? 'selected' : '') }}>250mm</option>
                                    </select>
                                </td>
                                <td>
                                    <div class="d-flex justify-content-center gap-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="load_test_result" id="loadTestResultPass"
                                                value="Pass" {{ (isset($inspection) && $inspection?->loadTest) ? (old('load_test_result', $inspection->loadTest->load_test_result) == 'Pass' ? 'checked' : '') : (old('load_test_result') == 'Pass' ? 'checked' : '') }}>
                                            <label class="form-check-label text-success" for="loadTestResultPass">
                                                Pass
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="load_test_result" id="loadTestResultFail"
                                                value="Fail" {{ (isset($inspection) && $inspection?->loadTest) ? (old('load_test_result', $inspection->loadTest->load_test_result) == 'Fail' ? 'checked' : '') : (old('load_test_result') == 'Fail' ? 'checked' : '') }}>
                                            <label class="form-check-label text-danger" for="loadTestResultFail">
                                                Fail
                                            </label>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Notes -->
        <div class="inspection-question mt-4">
            <label class="form-label fw-semibold">
                Additional Notes / Observations
            </label>
            <textarea class="form-control" name="load_test_notes" rows="3" placeholder="Record observations, anomalies, or other notes">{{ (isset($inspection) && $inspection?->loadTest) ? old('load_test_notes', $inspection->loadTest->load_test_notes) : old('load_test_notes') }}</textarea>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const templates = {
            pass: {
                load_test_duration: '15 Minutes',
                load_test_two_points_diagonal: '45 Degree',
                load_test_four_points: '45 Degree',
                load_test_deflection: 'N/A',
                load_test_deformation: 'N/A',
                load_test_distance_from_ground: 'Clear above ground',
                load_test_result: 'Pass',
                load_test_notes: 'Load test completed with no measurable deflection or deformation.'
            },
            fail: {
                load_test_duration: '5 Minutes',
                load_test_two_points_diagonal: '30 Degree',
                load_test_four_points: '30 Degree',
                load_test_deflection: '1/300',
                load_test_deformation: '0%',
                load_test_distance_from_ground: '50mm',
                load_test_result: 'Fail',
                load_test_notes: 'Load test failed due to measured deflection exceeding allowable limits.'
            }
        };

        document.querySelectorAll('.load-test-template').forEach(function (button) {
            button.addEventListener('click', function () {
                const templateKey = this.dataset.loadTestTemplate;
                const template = templates[templateKey];
                if (!template) {
                    return;
                }

                Object.entries(template).forEach(([field, value]) => {
                    const input = document.querySelector(`[name="${field}"]`);
                    if (!input) {
                        return;
                    }

                    if (input.tagName === 'SELECT') {
                        input.value = value;
                    } else if (input.type === 'radio') {
                        const radio = document.querySelector(`input[name="${field}"][value="${value}"]`);
                        if (radio) {
                            radio.checked = true;
                        }
                    } else {
                        input.value = value;
                    }
                });
            });
        });
    });
</script>