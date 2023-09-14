# Apteasy
### Apartman Yönetimi Uygulaması
Apteasy ile yönettiğiniz tüm apartmanların kayıtlarını tutabilir, apartmanlarınıza daire ve daire sakini ekleyebilir ve dairelere aidat bakiyesi ekleyip çıkarabilirsiniz.

Apteasy, twitch yayınlarında eğitim amaçlı geliştirilmekte olan bir uygulamadır. Bu sebeple **production** ortamında kullanılması sakıncalıdır.

Apteasy geliştirmeye açıktır, dilerseniz contributor olarak projeye dahil olabilirsiniz.

Eğitim sırasında dokümantasyon oluşturulacağı zaman bu README.md dosyası güncellenecektir.

### TODO
- [ ] Anasayfada toplam apartman, toplam daire, toplam yaşayan kişi ve toplam açık bakiye bilgileri kutu şeklinde görünür olsun.
- [ ] Otomatik olarak tüm apartmanlara aidat ekleyen bir betik olsun. Bu betik belirlediğimiz tarihte çalışıp, o aynı aidatını tüm dairelere eklesin.
- [ ] Her apartmanın aidat bilgisi farklı olacağından, apartman eklerken bu apartmanın aidat miktarını da yazabilelim.
- [ ] Bir apartmanın daireler listesinde "Transaction Ekle" butonu olsun. Ve aidat elden alınmışsa transaction manuel eklenebilsin.
- [ ] Bir apartmanın daireler listesinde "Transaction Ekle" butonu olsun. Aidat dışında ekstra masraf (ne olduğu ile birlikte) eklenebilsin. Örneğin "otomatik otopark kapısı anahtarı bedeli" gibi.
- [ ] Bir apartmanın daireler listesinde "Daireyi Boşalt" butonu olsun. Bu butona tıklayınca tüm daire sakinlerini silindi olarak işaretlesin. Silinme tarihini elle girebilelim.
- [ ] Daire sakini aidat ödemek için sisteme giriş yapabilmeli.
- [ ] Sisteme giriş yapabilen daire sakini sadece kendine özel sayfayı görebilmeli.
- [ ] Daire sakini sayfasında yalnızca transaction listesi, listede action olarak "Öde" butonu yer alsın.
- [ ] Daire sakini ödeme işlemini "Kredi Kartı" ile ödeyebilsin.
- [ ] Transaction listesinde "Ödeme Bildirimi Yap" butonu yer alsın. Kullanıcı banka havalesi ile ödemiş ise, bu yöntemi kullansın.
- [ ] Daire sakini "Ödeme Bildirimi" gönderdiğinde, ajax request yardımı ile Admin ekranında notification çıksın.
- [ ] Daire sakini "Ödeme Bildirimi" gönderdiğinde, socket.io yardımı ile Admin ekranında notification çıksın.
- [ ] Birden fazla dil ekleyebilelim.
- [ ] Sisteme TEMA desteği ekleyelim.
- [ ] YENİ TASARIM
- [ ] Giriş yapan kullanıcı için bir profil sayfası ekleyelim. Bu profil sayfasında kullanıcılar kendi bilgilerini güncelleyebilsinler.
- [ ] Kullanıcı 2FA ile giriş etkinleştirebilsin.
- [ ] Otomatik sistem epostaları olsun. 
- [ ] Herhangi bir borç eklendiğinde daire sakini eposta alsın.
- [ ] Herhangi bir borç ödendiğinde ya da ödeme bildirimi işleme alındığında daire sakini eposta alsın.
- [ ] Admin tüm bu epostaların birer kopyasını alsın.