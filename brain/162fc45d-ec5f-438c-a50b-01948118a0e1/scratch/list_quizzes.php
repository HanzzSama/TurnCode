<?php
require __DIR__ . '/../../vendor/autoload.php';
$app = require __DIR__ . '/../../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$quizzes = App\Models\Quiz::all();
foreach ($quizzes as $quiz) {
    echo "ID: " . $quiz->id . "\n";
    echo "Type: " . $quiz->type . "\n";
    echo "Question: " . $quiz->question . "\n";
    echo "Options: " . json_encode($quiz->options) . "\n";
    echo "Correct Answer: " . $quiz->correct_answer . "\n";
    echo "-------------------------------------\n";
}
