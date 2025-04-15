<?php
header('Content-Type: text/html; charset=utf-8');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Güncel Teknoloji Haberleri</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <header>
            <a href="https://techdergi.net" target="_blank" style="color: #fff; text-decoration: none;"><h1>Güncel Teknoloji Haberleri</h1></a>
            <h2>Tüm Teknoloji Haberleri Tek Bir Yerde!</h2>
            <p>Tüm Teknoloji Haberleri Aşağıda Listelenmektedir.</p>
            <p><a href="./" class="home-button">Anasayfaya Dön</a></p>
        </header>
        <main>
            <section class="search-section">
                <form method="get">
                    <input type="text" name="search" placeholder="Haber Ara..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                    <button type="submit">Ara</button>
                    <?php if (isset($_GET['time_filter'])): ?>
                        <input type="hidden" name="time_filter" value="<?php echo htmlspecialchars($_GET['time_filter']); ?>">
                    <?php endif; ?>
                    <?php if (isset($_GET['page'])): ?>
                        <input type="hidden" name="page" value="<?php echo htmlspecialchars($_GET['page']); ?>">
                    <?php endif; ?>
                </form>
            </section>
            <section class="filter-section">
                <form method="get">
                    <label for="time_filter">Zaman Filtresi:</label>
                    <select name="time_filter" id="time_filter" onchange="this.form.submit()">
                        <option value="">Tümü</option>
                        <option value="3600" <?php if (isset($_GET['time_filter']) && $_GET['time_filter'] == 3600) echo 'selected'; ?>>Son 1 Saat</option>
                        <option value="28800" <?php if (isset($_GET['time_filter']) && $_GET['time_filter'] == 28800) echo 'selected'; ?>>Son 8 Saat</option>
                        <option value="43200" <?php if (isset($_GET['time_filter']) && $_GET['time_filter'] == 43200) echo 'selected'; ?>>Son 12 Saat</option>
                        <option value="86400" <?php if (isset($_GET['time_filter']) && $_GET['time_filter'] == 86400) echo 'selected'; ?>>Son 24 Saat</option>
                    </select>
                    <?php if (isset($_GET['page'])): ?>
                        <input type="hidden" name="page" value="<?php echo htmlspecialchars($_GET['page']); ?>">
                    <?php endif; ?>
                    <?php if (isset($_GET['search'])): ?>
                        <input type="hidden" name="search" value="<?php echo htmlspecialchars($_GET['search']); ?>">
                    <?php endif; ?>
                </form>
            </section>
            <section class="news-list">
                <h2>En Son Haberler</h2>
                <?php
                $opmlFile = 'opml.opml';
                $itemsPerPage = 10;
                $timeFilterSeconds = isset($_GET['time_filter']) && is_numeric($_GET['time_filter']) ? (int) $_GET['time_filter'] : 0;
                $currentTime = time();
                $searchTerm = isset($_GET['search']) ? trim(htmlspecialchars($_GET['search'])) : '';

                if (file_exists($opmlFile)) {
                    $opmlContent = file_get_contents($opmlFile);
                    try {
                        $opml = new SimpleXmlElement($opmlContent);
                        $allNews = [];

                        foreach ($opml->body->outline as $category) {
                            foreach ($category->outline as $feed) {
                                $feedUrl = (string) $feed['xmlUrl'];
                                $feedTitle = (string) $feed['title'];

                                if ($feedUrl) {
                                    $content = @file_get_contents($feedUrl);
                                    if ($content !== false) {
                                        try {
                                            $xml = new SimpleXmlElement($content);
                                            foreach ($xml->channel->item as $item) {
                                                $pubDateTimestamp = strtotime($item->pubDate);
                                                if ($timeFilterSeconds > 0 && ($currentTime - $pubDateTimestamp) > $timeFilterSeconds) {
                                                    continue;
                                                }
                                                $title = (string) $item->title;
                                                $description = (string) $item->description;
                                                if (empty($searchTerm) || stripos($title, $searchTerm) !== false || stripos($description, $searchTerm) !== false) {
                                                    $imageUrl = 'varsayilan_resim.png'; // Varsayılan resim

                                                    // Görseli <enclosure> etiketinden almayı dene
                                                    if (isset($item->enclosure) && $item->enclosure['type'] == 'image/jpeg' || $item->enclosure['type'] == 'image/png') {
                                                        $imageUrl = (string) $item->enclosure['url'];
                                                    } else {
                                                        // Görseli description içindeki ilk <img> etiketinden almayı dene
                                                        preg_match('/<img.+src=[\'"](?P<src>.+?)[\'"].*?>/i', $description, $imageMatches);
                                                        if (isset($imageMatches['src']) && !empty($imageMatches['src'])) {
                                                            $imageUrl = $imageMatches['src'];
                                                        } else {
                                                            // Media RSS uzantılarını kontrol et
                                                            $media = $item->children('media', true);
                                                            if (isset($media->content) && isset($media->content->attributes()->url)) {
                                                                $imageUrl = (string) $media->content->attributes()->url;
                                                            } elseif (isset($media->thumbnail) && isset($media->thumbnail->attributes()->url)) {
                                                                $imageUrl = (string) $media->thumbnail->attributes()->url;
                                                            }
                                                        }
                                                    }

                                                    $allNews[] = [
                                                        'title' => $title,
                                                        'link' => (string) $item->link,
                                                        'description' => $description,
                                                        'pubDate' => $pubDateTimestamp,
                                                        'source' => htmlspecialchars($feedTitle),
                                                        'image' => $imageUrl,
                                                    ];
                                                }
                                            }
                                        } catch (Exception $e) {
                                            echo '<p class="error">RSS Kaynağı ayrıştırılamadı: ' . htmlspecialchars($feedTitle) . '</p>';
                                        }
                                    } else {
                                        echo '<p class="error">RSS Kaynağına ulaşılamadı: ' . htmlspecialchars($feedTitle) . '</p>';
                                    }
                                }
                            }
                        }

                        usort($allNews, function ($a, $b) {
                            return $b['pubDate'] - $a['pubDate'];
                        });

                        $totalNews = count($allNews);
                        $totalPages = ceil($totalNews / $itemsPerPage);
                        $currentPage = isset($_GET['page']) && is_numeric($_GET['page']) ? (int) $_GET['page'] : 1;
                        $currentPage = max(1, min($currentPage, $totalPages));
                        $startIndex = ($currentPage - 1) * $itemsPerPage;
                        $newsOnPage = array_slice($allNews, $startIndex, $itemsPerPage);

                        if (empty($newsOnPage)) {
                            echo '<p>Aranan kriterlere uygun haber bulunmuyor.</p>';
                        } else {
                            foreach ($newsOnPage as $newsItem) {
                                ?>
                                <div class="news-item">
                                    <div class="news-item-left">
                                        <img src="<?php echo htmlspecialchars($newsItem['image']); ?>" alt="Haber Resmi">
                                    </div>
                                    <div class="news-item-right">
                                        <h3><a href="<?php echo htmlspecialchars($newsItem['link']); ?>" target="_blank" rel="noopener noreferrer"><?php echo htmlspecialchars($newsItem['title']); ?></a></h3>
                                        <p class="news-source"><?php echo $newsItem['source']; ?></p>
                                        <p class="news-date"><?php echo date('d.m.Y H:i', $newsItem['pubDate']); ?></p>
                                        <p><?php echo htmlspecialchars(strip_tags($newsItem['description'])); ?></p>
                                        <a href="<?php echo htmlspecialchars($newsItem['link']); ?>" class="read-more" target="_blank" rel="noopener noreferrer">Haberi Oku</a>
                                    </div>
                                </div>
                                <?php
                            }
                        }

                        echo '<div class="pagination">';
                        for ($i = 1; $i <= $totalPages; $i++) {
                            $queryString = http_build_query(array_merge($_GET, ['page' => $i]));
                            echo '<a href="?' . $queryString . '">' . $i . '</a>';
                        }
                        echo '</div>';

                    } catch (Exception $e) {
                        echo '<p class="error">OPML dosyası ayrıştırılamadı.</p>';
                    }
                } else {
                    echo '<p class="error">OPML dosyası bulunamadı: ' . htmlspecialchars($opmlFile) . '</p>';
                }
                ?>
            </section>
        </main>
        <footer>
            <p>&copy; <?php echo date('Y'); ?> Tüm Hakları Saklıdır.</p>
        </footer>
    </div>
</body>
</html>