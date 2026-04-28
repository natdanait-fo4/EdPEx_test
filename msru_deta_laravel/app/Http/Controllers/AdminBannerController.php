<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use Illuminate\Http\Request;

class AdminBannerController extends Controller
{
    public function index()
    {
        $banners = Banner::orderBy('order_index')->get();
        return view('admin.banners.index', compact('banners'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:5120',
            'title' => 'nullable|string|max:255',
            'link' => 'nullable|string|max:255',
        ]);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $name = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('images/banners');
            
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }
            
            $image->move($destinationPath, $name);
            $imagePath = 'images/banners/' . $name;

            $lastOrder = Banner::max('order_index') ?? 0;

            Banner::create([
                'image_path' => $imagePath,
                'title' => $request->title,
                'link' => $request->link,
                'is_active' => true,
                'order_index' => $lastOrder + 1,
            ]);

            return redirect()->route('admin.banners.index')->with('success', 'อัปโหลดรูปภาพสำเร็จ');
        }

        return redirect()->back()->with('error', 'กรุณาเลือกรูปภาพ');
    }

    public function update(Request $request, $id)
    {
        $banner = Banner::findOrFail($id);
        $banner->is_active = !$banner->is_active;
        $banner->save();

        return redirect()->route('admin.banners.index')->with('success', 'อัปเดตสถานะสำเร็จ');
    }

    public function destroy($id)
    {
        $banner = Banner::findOrFail($id);
        
        // ลบไฟล์รูปภาพจริงใน folder public/images/banners
        $imagePath = public_path($banner->image_path);
        if (file_exists($imagePath) && is_file($imagePath)) {
            unlink($imagePath);
        }
        
        $banner->delete();

        return redirect()->route('admin.banners.index')->with('success', 'ลบแบนเนอร์และไฟล์รูปภาพเรียบร้อยแล้ว');
    }
}
