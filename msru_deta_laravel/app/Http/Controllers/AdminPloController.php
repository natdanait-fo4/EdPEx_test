<?php

namespace App\Http\Controllers;

use App\Models\Plo;
use Illuminate\Http\Request;

class AdminPloController extends Controller
{
    public function index()
    {
        $plos = Plo::orderBy('order_index')->get();
        return view('admin.plos.index', compact('plos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:10240',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'icon_class' => 'nullable|string|max:255',
            'degree_level' => 'required|in:bachelor,master,doctoral',
        ]);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $name = time() . '_plo_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('images/plos');
            
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }
            
            $image->move($destinationPath, $name);
            $imagePath = 'images/plos/' . $name;

            $lastOrder = Plo::max('order_index') ?? 0;

            Plo::create([
                'title' => $request->title,
                'description' => $request->description,
                'icon_class' => $request->icon_class ?? 'fa-solid fa-bullhorn',
                'degree_level' => $request->degree_level,
                'image_path' => $imagePath,
                'is_active' => true,
                'order_index' => $lastOrder + 1,
            ]);

            return redirect()->route('admin.banners.index')->with('success', 'เพิ่มรูปภาพ PLO สำเร็จ');
        }

        return redirect()->back()->with('error', 'กรุณาเลือกรูปภาพ');
    }

    public function update(Request $request, $id)
    {
        $plo = Plo::findOrFail($id);

        if ($request->has('toggle_status')) {
            $plo->is_active = !$plo->is_active;
            $plo->save();
            return redirect()->route('admin.banners.index')->with('success', 'อัปเดตสถานะสำเร็จ');
        }

        $request->validate([
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:10240',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'icon_class' => 'nullable|string|max:255',
            'is_active' => 'required|boolean',
            'degree_level' => 'required|in:bachelor,master,doctoral',
        ]);

        if ($request->hasFile('image')) {
            // ลบรูปภาพตัวเก่าออกจากเครื่อง
            if ($plo->image_path) {
                $oldImagePath = public_path($plo->image_path);
                if (file_exists($oldImagePath) && is_file($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }

            // บันทึกรูปภาพใหม่
            $image = $request->file('image');
            $name = time() . '_plo_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('images/plos');
            
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }
            
            $image->move($destinationPath, $name);
            $plo->image_path = 'images/plos/' . $name;
        }

        $plo->title = $request->title;
        $plo->description = $request->description;
        $plo->icon_class = $request->icon_class ?? 'fa-solid fa-bullhorn';
        $plo->degree_level = $request->degree_level;
        $plo->is_active = (bool)$request->is_active;
        $plo->save();

        return redirect()->route('admin.banners.index')->with('success', 'แก้ไขรายละเอียด PLO เรียบร้อยแล้ว');
    }

    public function destroy($id)
    {
        $plo = Plo::findOrFail($id);
        
        // ลบไฟล์รูปภาพจริงใน folder public/images/plos
        if ($plo->image_path) {
            $imagePath = public_path($plo->image_path);
            if (file_exists($imagePath) && is_file($imagePath)) {
                unlink($imagePath);
            }
        }
        
        $plo->delete();

        return redirect()->route('admin.banners.index')->with('success', 'ลบรูปภาพ PLO เรียบร้อยแล้ว');
    }

    public function reorder(Request $request)
    {
        $request->validate([
            'order' => 'required|array',
            'order.*' => 'integer|exists:plos,id'
        ]);

        foreach ($request->order as $index => $id) {
            Plo::where('id', $id)->update(['order_index' => $index + 1]);
        }

        return response()->json(['success' => true]);
    }
}
