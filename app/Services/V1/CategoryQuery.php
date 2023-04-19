<?php

namespace App\Services\V1;
use Illuminate\Http\Request;
/**
 * Truy vấn sách
 */
class CategoryQuery{
    /**
     * định nghĩa lọc cho từng trường giá trị
     */
    protected $safeParms = [
        'name' =>['eq'],
    ];
    /**
     * 
     */
    protected $columnMap = [
        //
    ];
    /**
     * định nghĩa cho phép so sánh
     */
    protected $operatorMap =[
        'eq' => '=',
    ];
    /**
     * Lọc
     */
    public function transform(Request $request){
        $eloQuery = [];
        foreach($this->safeParms as $parm => $operators){
            $query = $request->query($parm);

            if(!isset($query)){continue;}

            $comlumn = $this->columnMap[$parm]?? $parm; 

            foreach($operators as $operator){
                if(isset($query[$operator])){
                   $eloQuery[] = [$comlumn, $this->operatorMap[$operator],$query[$operator]];
                }
            }
        }

        return $eloQuery;
    }
}