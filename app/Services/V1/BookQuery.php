<?php 

namespace App\Services\V1;

use Illuminate\Http\Request;

class BookQuery{
    protected $safeParms =[
        'name' => ['eq'],
        'categoryId' => ['eq'],
        'quantity' => ['eq','gt','lt'],
        'price' => ['eq','gt','lt'],
        'loanPrice' => ['eq','gt','lt'],
        'status' => ['eq'],
        'author' => ['eq'],
        'publishedAt' => ['eq'],
    ];
    protected $columnMap = [
        'loanPrice'=>'loan_price',
        'categoryId'=>'category_id',
        'publishedAt'=>'published_at',
    ];
    protected $operatorMap = [
        'eq' => '=',
        'lt' => '<',
        'gt' => '>',
        'lte' => '<=',
        'gte' => '>=',
    ];
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