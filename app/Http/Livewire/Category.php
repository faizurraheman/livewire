<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Category as Categories;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class Category extends Component
{
    public $categories, $name, $description, $category_id, $image;
    public $updateCategory = false;
    use WithFileUploads;

    protected $listeners = [
        'deleteCategory'=>'destroy'
    ];

    protected $rules = [
        'name'=>'required',
        'description'=>'required',
        'image' => 'required',
    ];

    public function render()
    {
        $this->categories = Categories::get();

        return view('livewire.category');
    }

    public function resetFields(){
        $this->name = '';
        $this->description = '';
        $this->image ='';
    }

    public function add(){
        // Validate Form Request
        $this->validate();

        try{
            $path = $this->image->store('images', 'public');

            Categories::create([
                'name'=>$this->name,
                'description'=>$this->description,
                'image'=>$path
            ]);

            $this->dispatchBrowserEvent('alert',[
                'type'=>'success',
                'message'=>"Category Created Successfully!!"
            ]);
            $this->resetFields();
        }catch(\Exception $e){
            $this->dispatchBrowserEvent('alert',[
                'type'=>'error',
                'message'=>"Something goes wrong while creating category!!"
            ]);

            // Reset Form Fields After Creating Category
            $this->resetFields();
        }
    }

    public function edit($id){
        $category = Categories::findOrFail($id);
        $this->name = $category->name;
        $this->description = $category->description;
        $this->image = $category->image;
        $this->category_id = $category->id;
        $this->updateCategory = true;
    }

    public function cancel()
    {
        $this->updateCategory = false;
        $this->resetFields();
    }

    public function update(){

        // Validate request
        $this->validate();

        try{
            $category = Categories::find($this->category_id)->first();
            $oldImagePath = $category->image;

        // Handle file upload manually
        if ($this->image instanceof UploadedFile) {
            $extension = $this->image->getClientOriginalExtension();
            $fileName = 'new_image_' . time() . '.' . $extension;
            $path = $this->image->storeAs('images', $fileName, 'public');
            if (!empty($oldImagePath)) {
                Storage::disk('public')->delete($oldImagePath);
            }
        } else {
            // If no new image is provided, keep the existing image path
            $path = $oldImagePath;
        }
            // Update category
            Categories::find($this->category_id)->fill([
                'name'=>$this->name,
                'description'=>$this->description,
                'image'=>$path
            ])->save();

            $this->dispatchBrowserEvent('alert',[
                'type'=>'success',
                'message'=>"Category Updated Successfully!!"
            ]);

            $this->cancel();
        }catch(\Exception $e){
            $this->dispatchBrowserEvent('alert',[
                'type'=>'error',
                'message'=>"Something goes wrong while updating category!!"
            ]);
            $this->cancel();
        }
    }

    public function destroy($id){
        try{
            $category = Categories::find($id)->first();
            $oldImagePath = $category->image;
            if (!empty($oldImagePath)) {
                Storage::disk('public')->delete($oldImagePath);
            }
            Categories::find($id)->delete();
            $this->dispatchBrowserEvent('alert',[
                'type'=>'success',
                'message'=>"Category Deleted Successfully!!"
            ]);
        }catch(\Exception $e){
            $this->dispatchBrowserEvent('alert',[
                'type'=>'success',
                'message'=>"Something goes wrong while deleting category!!"
            ]);
        }
    }
}
