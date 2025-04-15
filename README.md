# Basit RSS Feed Listeleme Web Sitesi

Bu proje, PHP, HTML ve CSS kullanılarak geliştirilmiş basit bir web sitesidir. Amacı, verilen bir OPML (Outline Processor Markup Language) dosyasındaki RSS feedlerini otomatik olarak tarayarak en güncel haberleri listelemektir. Haberler, yayın tarihlerine göre en yeniden en eskiye doğru sıralanır.

## Özellikler

* **OPML Desteği:** Kullanıcı tarafından sağlanan bir OPML dosyasındaki tüm RSS feedlerini okuyabilir.
* **Otomatik Tarama:** OPML dosyasındaki feed URL'lerini otomatik olarak tarar.
* **En Yeni Haberler Üstte:** Haberler, yayın tarihlerine göre sıralanır ve en yeni haberler en üstte görüntülenir.
* **Sayfalama:** Çok sayıda haberin olduğu durumlarda sayfalandırma özelliği sayesinde içerik daha yönetilebilir bir şekilde sunulur.
* **Zaman Filtreleme:** Kullanıcılar, haberleri son 1 saat, 8 saat, 12 saat veya 24 saat gibi belirli zaman aralıklarına göre filtreleyebilirler.
* **Arama:** Sayfa içinde haber başlıkları ve açıklamalarında arama yapma imkanı sunar.
* **Otomatik Görsel Çekme:** RSS feed'lerinde belirtilen haber görsellerini otomatik olarak çekip görüntüler (mümkün olduğunca).
* **Anasayfaya Dönüş:** Arama veya filtreleme yapıldıktan sonra kolayca anasayfaya dönme butonu bulunur.

## Kullanılan Teknolojiler

* PHP
* HTML
* CSS

## Kurulum

1.  Bu kodu web sunucunuzun erişebileceği bir klasöre yükleyin.
2.  `opml.opml` adında bir dosya oluşturun ve takip etmek istediğiniz RSS feedlerinin URL'lerini içeren OPML içeriğinizi bu dosyaya yapıştırın. Bu dosyayı `index.php` ile aynı klasöre yerleştirin.
3.  İsteğe bağlı olarak, haberlerde görsel bulunamadığında gösterilecek bir `varsayilan_resim.png` dosyası oluşturup aynı klasöre ekleyebilirsiniz.

## Güncel Teknoloji Haberleri

En son teknoloji gelişmelerini takip etmek ve daha fazla **Güncel Teknoloji Haberleri** okumak için [TechDergi](https://techdergi.net) adresini ziyaret edebilirsiniz.

## Katkıda Bulunma

Bu proje açık kaynaklıdır ve katkılarınıza açıktır. Hata bildirimleri veya iyileştirme önerileriniz için lütfen bir "issue" oluşturun veya "pull request" gönderin.

Harika! Apache Lisansı 2.0'ı seçtiyseniz, README.md dosyanızdaki lisans bölümünü aşağıdaki gibi güncelleyebilirsiniz:

Markdown

## Lisans

Bu proje, Apache Lisansı 2.0 altında lisanslanmıştır. Lisansın tam metnini [buradan](https://www.apache.org/licenses/LICENSE-2.0) okuyabilirsiniz.
