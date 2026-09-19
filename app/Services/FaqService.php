<?php

namespace App\Services;

use App\Models\FAQ;

class FaqService
{
    public function __construct(){}

    public function getAllFaq()
    {
        $faq = FAQ::get();
        return $faq ;
    }
    public function storeFaq(array $data)
    {
        $faq = FAQ::create([
            'question' => $data['question'],
            'answer' => $data['answer'],
        ]);
        return $faq ;
    }


    public function updateFaq(FAQ $faq , array $data)
    {
        $faq->update([
            'question' => $data['question'],
            'answer' => $data['answer'],
        ]);
        return $faq;
    }

    public function deleteFaq(FAQ $faq)
    {
        $faq->delete();
        return $faq;
    }
}