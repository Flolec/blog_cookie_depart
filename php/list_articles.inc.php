<?php

use Blog\ArticleRepository;

$message = '';
$articleRepository = new ArticleRepository();
$articles = $articleRepository->getAllArticles($message);
$message .= ($articles && count($articles) < 1) ? 'Pas d\' article pour le moment' : '';
