<?php
$section = $_GET['section'] ?? 'civil';

$userRole = 'user'; 

$sections = [
    'civil' => [
        'title' => 'Гражданское право',
        'color' => 'primary',
        'bookDir' => 'pages/books/civil.books/',
        'codes' => [
            ['title' => 'Гражданский кодекс РФ', 'file' => 'pages/codes/civil_code.pdf'],
            ['title' => 'Жилищный кодекс РФ', 'file' => 'pages/codes/housing_code.pdf'],
        ],
        'templates' => [
            'Договор купли-продажи',
            'Договор вступления в наследство',
            'Договор дарения'
        ]
    ],
    'criminal' => [
        'title' => 'Уголовное право',
        'color' => 'danger',
        'bookDir' => 'pages/books/criminal.books/',
        'codes' => [
            ['title' => 'Уголовный кодекс РФ', 'file' => 'pages/codes/criminal_code.pdf'],
            ['title' => 'Уголовно-процессуальный кодекс РФ', 'file' => 'pages/codes/upk.pdf'],
        ],
        'templates' => [
            'Ходатайство об УДО',
            'Апелляционная жалоба',
            'Ходатайство о примирении'
        ]
    ],
    'admin' => [
        'title' => 'Административное право',
        'color' => 'success',
        'bookDir' => 'pages/books/admin.books/',
        'codes' => [
            ['title' => 'КоАП РФ', 'file' => 'pages/codes/koap.pdf'],
            ['title' => 'Федеральный закон "О порядке рассмотрения обращений граждан"', 'file' => 'pages/codes/obrashcheniya.pdf'],
        ],
        'templates' => [
            'Жалоба в прокуратуру',
            'Заявление в суд об оспаривании постановления',
            'Ходатайство об отложении рассмотрения дела'
        ]
    ]
];
$current = $sections[$section] ?? $sections['civil'];
$title = $current['title'];
$color = $current['color'];

$bookDir = $current['bookDir'];
$books = [];
if (is_dir($_SERVER['DOCUMENT_ROOT'] . '/' . $bookDir)) {
    $files = glob($_SERVER['DOCUMENT_ROOT'] . '/' . $bookDir . '*.pdf');
    foreach ($files as $file) {
        $filename = basename($file);
        $titleClean = str_replace(['_', '.pdf'], [' ', ''], $filename);
        $titleClean = preg_replace('/([a-z])([A-Z])/', '$1 $2', $titleClean); 
        $books[] = [
            'url' => $bookDir . $filename,
            'title' => $titleClean
        ];
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .book-link::before { content: "📄 "; }
        .code-link::before { content: "⚖️ "; }
    </style>
</head>
<body class="bg-light">
   

    <div class="container mt-4 mb-5">
        <h1 class="text-center mb-5 text-<?= $color ?>"><?= htmlspecialchars($title) ?></h1>

        <section class="mb-5">
            <h2 class="text-<?= $color ?> border-bottom pb-2">📚 Книги</h2>
            <?php if (!empty($books)): ?>
                <div class="list-group">
                    <?php foreach ($books as $book): ?>
                        <a href="<?= htmlspecialchars($book['url']) ?>" 
                           class="list-group-item list-group-item-action d-flex align-items-center book-link"
                           target="_blank" rel="noopener">
                            <?= htmlspecialchars($book['title']) ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="alert alert-warning">
                    Книги пока не загружены в папку <code><?= htmlspecialchars($bookDir) ?></code>.
                </div>
            <?php endif; ?>
        </section>

        <section class="mb-5">
            <h2 class="text-<?= $color ?> border-bottom pb-2">⚖️ Кодексы</h2>
            <div class="list-group">
                <?php foreach ($current['codes'] as $code): ?>
                    <a href="<?= htmlspecialchars($code['file']) ?>" 
                       class="list-group-item list-group-item-action code-link"
                       target="_blank" rel="noopener">
                        <?= htmlspecialchars($code['title']) ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </section>

        <section>
            <h2 class="text-<?= $color ?> border-bottom pb-2">📝 Шаблоны документов</h2>
            <?php foreach ($current['templates'] as $index => $name): ?>
                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($name) ?></h5>
                        <textarea class="form-control mb-3" rows="4" 
                            placeholder="Введите данные: ФИО, адрес, дата и т.д."></textarea>
                        <div class="d-flex flex-wrap gap-2">
                            <button class="btn btn-<?= $color ?>" 
                                onclick="alert('⚠️ Принтер не подключён.\n\nВ реальной версии здесь будет генерация PDF.')">
                                Печать
                            </button>
                            <?php if ($userRole === 'admin'): ?>
                                <button class="btn btn-outline-secondary">✏️ Редактировать</button>
                                <button class="btn btn-outline-danger">🗑️ Удалить</button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </section>

        <div class="text-center mt-4">
            <a href="index.php" class="btn btn-outline-<?= $color ?>">&larr; На главную</a>
        </div>
    </div>
</body>
</html>