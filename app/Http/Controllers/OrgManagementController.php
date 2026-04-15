<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Constants\Organization;
use Illuminate\Support\Facades\File;

class OrgManagementController extends Controller
{
    public function index()
    {
        $deptDivisions = Organization::DEPT_DIVISIONS;
        $locations = Organization::LOCATIONS;
        
        return view('organization.manage', compact('deptDivisions', 'locations'));
    }

    public function update(Request $request)
    {
        // For simplicity, we expect the entire structure to be sent but we'll build it carefully.
        // We'll handle 'add_dept', 'edit_dept', 'delete_dept', 'add_div', 'edit_div', 'delete_div', 'add_loc', 'edit_loc', 'delete_loc' actions.
        
        $currentDeptDivisions = Organization::DEPT_DIVISIONS;
        $currentLocations = Organization::LOCATIONS;
        
        $action = $request->input('action');
        
        switch ($action) {
            case 'add_department':
                $name = strtoupper(trim($request->input('name')));
                if ($name && !isset($currentDeptDivisions[$name])) {
                    $currentDeptDivisions[$name] = [];
                }
                break;
                
            case 'edit_department':
                $oldName = $request->input('old_name');
                $newName = strtoupper(trim($request->input('new_name')));
                if ($oldName && $newName && isset($currentDeptDivisions[$oldName])) {
                    $divisions = $currentDeptDivisions[$oldName];
                    unset($currentDeptDivisions[$oldName]);
                    $currentDeptDivisions[$newName] = $divisions;
                    
                    // Propagate to database
                    \App\Models\Employee::where('department', $oldName)->update(['department' => $newName]);
                    \App\Models\PcUnit::where('department', $oldName)->update(['department' => $newName]);
                    \App\Models\Printer::where('department', $oldName)->update(['department' => $newName]);
                    \App\Models\NetworkDevice::where('department', $oldName)->update(['department' => $newName]);
                }
                break;
                
            case 'delete_department':
                $name = $request->input('name');
                if (isset($currentDeptDivisions[$name])) {
                    // Check if in use
                    $inUse = \App\Models\Employee::where('department', $name)->exists() ||
                             \App\Models\PcUnit::where('department', $name)->exists() ||
                             \App\Models\Printer::where('department', $name)->exists() ||
                             \App\Models\NetworkDevice::where('department', $name)->exists();
                    
                    if ($inUse) {
                        return back()->with('error', "Cannot delete department '$name' because it is currently assigned to employees or assets. Please reassign them first.");
                    }
                    unset($currentDeptDivisions[$name]);
                }
                break;
                
            case 'add_division':
                $dept = $request->input('department');
                $divName = strtoupper(trim($request->input('name')));
                if ($dept && $divName && isset($currentDeptDivisions[$dept])) {
                    if (!in_array($divName, $currentDeptDivisions[$dept])) {
                        $currentDeptDivisions[$dept][] = $divName;
                    }
                }
                break;
                
            case 'edit_division':
                $dept = $request->input('department');
                $oldName = $request->input('old_name');
                $newName = strtoupper(trim($request->input('new_name')));
                if ($dept && $oldName && $newName && isset($currentDeptDivisions[$dept])) {
                    $key = array_search($oldName, $currentDeptDivisions[$dept]);
                    if ($key !== false) {
                        $currentDeptDivisions[$dept][$key] = $newName;
                        
                        // Propagate to database
                        \App\Models\Employee::where('department', $dept)->where('division', $oldName)->update(['division' => $newName]);
                        \App\Models\PcUnit::where('department', $dept)->where('division', $oldName)->update(['division' => $newName]);
                        \App\Models\Printer::where('department', $dept)->where('division', $oldName)->update(['division' => $newName]);
                        \App\Models\NetworkDevice::where('department', $dept)->where('division', $oldName)->update(['division' => $newName]);
                    }
                }
                break;
                
            case 'delete_division':
                $dept = $request->input('department');
                $name = $request->input('name');
                if ($dept && $name && isset($currentDeptDivisions[$dept])) {
                    $key = array_search($name, $currentDeptDivisions[$dept]);
                    if ($key !== false) {
                        // Check if in use
                        $inUse = \App\Models\Employee::where('department', $dept)->where('division', $name)->exists() ||
                                 \App\Models\PcUnit::where('department', $dept)->where('division', $name)->exists() ||
                                 \App\Models\Printer::where('department', $dept)->where('division', $name)->exists() ||
                                 \App\Models\NetworkDevice::where('department', $dept)->where('division', $name)->exists();

                        if ($inUse) {
                            return back()->with('error', "Cannot delete division '$name' because it is currently assigned to employees or assets in $dept.");
                        }
                        
                        unset($currentDeptDivisions[$dept][$key]);
                        $currentDeptDivisions[$dept] = array_values($currentDeptDivisions[$dept]);
                    }
                }
                break;

            case 'add_location':
                $name = strtoupper(trim($request->input('name')));
                if ($name && !in_array($name, $currentLocations)) {
                    $currentLocations[] = $name;
                }
                break;

            case 'edit_location':
                $oldName = $request->input('old_name');
                $newName = strtoupper(trim($request->input('new_name')));
                if ($oldName && $newName) {
                    $key = array_search($oldName, $currentLocations);
                    if ($key !== false) {
                        $currentLocations[$key] = $newName;
                        
                        // Propagate to database
                        \App\Models\Employee::where('group', $oldName)->update(['group' => $newName]);
                        \App\Models\PcUnit::where('group', $oldName)->update(['group' => $newName]);
                        \App\Models\Printer::where('group', $oldName)->update(['group' => $newName]);
                        \App\Models\NetworkDevice::where('group', $oldName)->update(['group' => $newName]);
                    }
                }
                break;

            case 'delete_location':
                $name = $request->input('name');
                $key = array_search($name, $currentLocations);
                if ($key !== false) {
                    // Check if in use
                    $inUse = \App\Models\Employee::where('group', $name)->exists() ||
                             \App\Models\PcUnit::where('group', $name)->exists() ||
                             \App\Models\Printer::where('group', $name)->exists() ||
                             \App\Models\NetworkDevice::where('group', $name)->exists();

                    if ($inUse) {
                        return back()->with('error', "Cannot delete location '$name' because it is currently assigned to employees or assets.");
                    }
                    
                    unset($currentLocations[$key]);
                    $currentLocations = array_values($currentLocations);
                }
                break;
        }

        $this->saveToConstants($currentDeptDivisions, $currentLocations);

        return redirect()->route('organization.manage')->with('success', 'Organization structure updated successfully.');
    }

    private function saveToConstants($deptDivisions, $locations)
    {
        // Compute DEPARTMENTS and DIVISIONS
        $departments = array_keys($deptDivisions);
        $divisions = [];
        foreach ($deptDivisions as $dept => $divs) {
            foreach ($divs as $div) {
                if (!in_array($div, $divisions)) {
                    $divisions[] = $div;
                }
            }
        }
        
        // Also add sub-offices from locations to divisions to match previous behavior if any
        // Looking at the original file, some sub-offices were in DIVISIONS list but not in DEPT_DIVISIONS hierarchy explicitly for all.
        // Actually, I'll just keep DEPARTMENTS, LOCATIONS, and DIVISIONS as flat lists for the dropdowns.
        
        $filePath = app_path('Constants/Organization.php');
        
        $content = "<?php\n\nnamespace App\Constants;\n\nclass Organization\n{\n";
        
        $content .= "    public const DEPT_DIVISIONS = " . $this->prettyPrintArray($deptDivisions, 4) . ";\n\n";
        $content .= "    public const DEPARTMENTS = " . $this->prettyPrintArray($departments, 4) . ";\n\n";
        $content .= "    public const LOCATIONS = " . $this->prettyPrintArray($locations, 4) . ";\n\n";
        $content .= "    public const DIVISIONS = " . $this->prettyPrintArray($divisions, 4) . ";\n";
        
        $content .= "}\n";
        
        File::put($filePath, $content);
    }

    private function prettyPrintArray($array, $indent = 0)
    {
        $export = var_export($array, true);
        $export = preg_replace("/^([ ]*)(.*)/m", '$1$1$2', $export);
        $export = array_filter(explode("\n", $export));
        $indentStr = str_repeat(' ', $indent);
        
        $result = [];
        foreach ($export as $line) {
            $result[] = $indentStr . $line;
        }
        
        $output = trim(implode("\n", $result));
        
        // Clean up from var_export format to slightly cleaner PHP 7+ style
        $output = str_replace('array (', '[', $output);
        $output = str_replace('),', '],', $output);
        $output = preg_replace('/\)$/', ']', $output);
        
        return $output;
    }
}
