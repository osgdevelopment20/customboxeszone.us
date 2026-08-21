<?php
namespace App\Services;
use Spatie\SchemaOrg\Schema;

class SchemaService
{
    public function generateFaqSchema(array $faqs): string
    {
        if (empty($faqs)) {
            return '';
        }

        $schema = Schema::fAQPage()
            ->identifier(route('faq'))
            ->name('Frequently Asked Questions')
            ->description('Have a question in mind? Navigate through these questions to find answers.');

        $mainEntity = $this->buildFaqQuestions($faqs);

        if (!empty($mainEntity)) {
            $schema->mainEntity($mainEntity);
            return $schema->toScript();
        }

        return '';
    }

    private function buildFaqQuestions(array $faqs): array
    {
        return collect($faqs)
            ->filter(function ($faq) {
                return !empty($faq['question'])
                    && !empty($faq['answer'])
                    && is_string($faq['question'])
                    && is_string($faq['answer']);
            })
            ->map(function ($faq) {
                return Schema::question()
                    ->name(trim($faq['question']))
                    ->acceptedAnswer(
                        Schema::answer()->text(trim($faq['answer']))
                    );
            })
            ->toArray();
    }
}
