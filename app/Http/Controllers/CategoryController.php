<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    //



//1
    public function getAllCat($parent_id=0)
    {
        //get main cat and children 
        
                $Main_Cat=DB::select('select * from categories where parent_id= '.$parent_id);
                $arr=[];
                foreach($Main_Cat as $subCat)
                {
                    $arr[]=[
                      'id'=>$subCat->id,
                      'parent_id'=>$subCat->parent_id,
                      'category_name'=>$subCat->name,
                      'child'=>$this->getAllCat($subCat->id)
                    ];  
                    
                    
               }

               
              return $arr;
               
               
               

        } 





//2 by id get cat with children of children 
    public function getparent_child($id)
    {

        //get main cat and children  by id 
                $main_cat=DB::select('select name from categories where id='.$id);
                $arr[]= ['MAinCat'=>$main_cat,
                            'child'=>$this->sortMax2Min($id)
                           ];
                           return response($arr, 200);
                
            }
   


            public function sortMax2Min($id)
            {
                $Main_Cat=DB::select('select * from categories where parent_id= '.$id);
                $arr=[];
                foreach($Main_Cat as $subCat)
                {
                    $arr[]=[
                      'id'=>$subCat->id,
                      'parent_id'=>$subCat->parent_id,
                      'category_name'=>$subCat->name,
                      'child'=>$this->sortMax2Min($subCat->id)
                    ];  
                    
                    
               }

               
              return $arr;
            }

            //3sub
            public function byChildId($id)
            {

                   $main_cat=DB::select('select name from categories where id='.$id);
                        $arr[]= ['child cat name'=>$main_cat,
                                    'parent name'=>$this->sortMin2Max($id)
                                   ];
                                   return response($arr, 200);
                        
                    }

            public function sortMin2Max($id)
            {
                $child_cat=DB::select('select * from categories where id= '.$id);
                
                $arr=[];
                foreach($child_cat as $subCat)
                {
                    $arr[]=[
                      'id'=>$subCat->id,
                      'child_id'=>$subCat->parent_id,
                      'category_name'=>$subCat->name,
                      'parent_id'=>$this->sortMin2Max($subCat->parent_id)
                    ];  
                    
                    
               }

               
              return $arr;
            }



    public function index()
    {
            //get grand son by grandfather id 
        $result=DB::select('SELECT gc.name
        FROM categories p
        JOIN categories c ON c.parent_id = p.id
        JOIN categories gc ON gc.parent_id = c.id
        WHERE p.id = 2');
        return $result; 

    }
           //1 done 
      
           
    
   // find thd parent by child id 


    
    
    }
