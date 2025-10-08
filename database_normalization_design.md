# Database Normalization Design

## Current Problem
The current `inspections` table contains 49 service-specific columns that result in many NULL values when an inspection doesn't use all services. This violates database normalization principles and creates maintenance issues.

## Proposed Solution
Separate service-specific data into dedicated tables with proper foreign key relationships.

## New Database Schema

### 1. Core Inspections Table (Cleaned)
```sql
inspections:
- id (Primary Key)
- inspection_number
- client_id (Foreign Key)
- project_id (Foreign Key) 
- location_id (Foreign Key)
- service_id (Foreign Key)
- equipment_id (Foreign Key)
- purchase_order
- client_job_reference
- rig
- report_number
- revision
- job_ref
- standards
- local_procedure_number
- drawing_number
- test_restrictions
- surface_condition
- inspection_date
- weather_conditions
- temperature
- humidity
- equipment_type
- equipment_description
- manufacturer
- model
- serial_number
- asset_tag
- manufacture_year
- capacity
- capacity_unit
- applicable_standard
- inspection_class
- certification_body
- previous_certificate_number
- last_inspection_date
- next_inspection_due
- next_inspection_date
- defects_found
- recommendations
- limitations
- lead_inspector_name
- lead_inspector_certification
- inspector_signature
- report_date
- general_notes
- inspector_comments
- inspection_images
- attachments
- status
- qa_status
- completed_by
- completed_at
- created_by
- created_at
- updated_at
- overall_result
- service_notes
- qa_reviewer_id
- qa_reviewed_at
- qa_comments
- qa_rejection_reason
```

### 2. MPI Inspections Table
```sql
mpi_inspections:
- id (Primary Key)
- inspection_id (Foreign Key to inspections.id)
- mpi_service_inspector
- visual_inspector
- visual_comments
- visual_method
- visual_lighting
- visual_equipment
- visual_conditions
- visual_results
- created_at
- updated_at
```

### 3. Lifting Examinations Table
```sql
lifting_examinations:
- id (Primary Key)
- inspection_id (Foreign Key to inspections.id)
- lifting_examination_inspector
- thorough_examination_inspector
- thorough_examination_comments
- thorough_method
- thorough_equipment
- thorough_conditions
- thorough_results
- created_at
- updated_at
```

### 4. Load Tests Table
```sql
load_tests:
- id (Primary Key)
- inspection_id (Foreign Key to inspections.id)
- load_test_inspector
- load_test_duration
- load_test_two_points_diagonal
- load_test_four_points
- load_test_deflection
- load_test_deformation
- load_test_distance_from_ground
- load_test_result
- load_test_notes
- created_at
- updated_at
```

### 5. Other Tests Table
```sql
other_tests:
- id (Primary Key)
- inspection_id (Foreign Key to inspections.id)
-- Drop Test Fields
- drop_test_load
- drop_type
- drop_distance
- drop_suspended
- drop_impact_speed
- drop_result
- drop_notes
-- Tilt Test Fields
- tilt_test_load
- loaded_tilt
- empty_tilt
- tilt_results
- tilt_stability
- tilt_direction
- tilt_duration
- tilt_notes
-- Lowering Test Fields
- lowering_test_load
- lowering_impact_speed
- lowering_result
- lowering_method
- lowering_distance
- lowering_duration
- lowering_cycles
- brake_efficiency
- control_response
- lowering_notes
- created_at
- updated_at
```

## Relationships

### One-to-One Relationships
- `inspections` → `mpi_inspections` (optional)
- `inspections` → `lifting_examinations` (optional)
- `inspections` → `load_tests` (optional)
- `inspections` → `other_tests` (optional)

### Foreign Key Constraints
- All service tables have `inspection_id` with CASCADE DELETE
- Only create service records when that service is actually performed

## Benefits

1. **Reduced NULL Values**: Service tables only exist when services are performed
2. **Better Performance**: Smaller main table, faster queries
3. **Maintainability**: Easy to add new service types without altering main table
4. **Data Integrity**: Clear separation of concerns
5. **Scalability**: Can easily add new service types as separate tables

## Migration Strategy

1. Create new service tables
2. Migrate existing data from inspections table to service tables
3. Remove service-specific columns from inspections table
4. Update models and relationships
5. Update controller logic to work with new structure

## Model Relationships (Laravel)

```php
// Inspection Model
class Inspection extends Model {
    public function mpiInspection() {
        return $this->hasOne(MpiInspection::class);
    }
    
    public function liftingExamination() {
        return $this->hasOne(LiftingExamination::class);
    }
    
    public function loadTest() {
        return $this->hasOne(LoadTest::class);
    }
    
    public function otherTests() {
        return $this->hasOne(OtherTest::class);
    }
}

// Service Models
class MpiInspection extends Model {
    public function inspection() {
        return $this->belongsTo(Inspection::class);
    }
}

class LiftingExamination extends Model {
    public function inspection() {
        return $this->belongsTo(Inspection::class);
    }
}

class LoadTest extends Model {
    public function inspection() {
        return $this->belongsTo(Inspection::class);
    }
}

class OtherTest extends Model {
    public function inspection() {
        return $this->belongsTo(Inspection::class);
    }
}
```