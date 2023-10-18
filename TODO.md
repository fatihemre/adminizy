### BİR SONRAKİ YAYINDA YAPILACAKLAR
- [ ] (vacate-the-flat) Bir apartmanın daireler listesinde "Daireyi Boşalt" butonu olsun. Bu butona tıklayınca tüm daire sakinlerini silindi olarak işaretlesin. Silinme tarihini elle girebilelim.
- [ ] (transactions) Tüm parasal işlemleri transactions adlı tabloda toparlayalım. Tablo yapısını yayın sırasında planlarız.
- [ ] (add-transaction) Bir apartmanın daireler listesinde "Transaction Ekle" butonu olsun. Ve aidat elden alınmışsa transaction manuel eklenebilsin.
- [ ] (add-expense) Bir apartmanın daireler listesinde "Transaction Ekle" butonu olsun. Aidat dışında ekstra masraf (ne olduğu ile birlikte) eklenebilsin. Örneğin "otomatik otopark kapısı anahtarı bedeli" gibi.

### TODOLIST
- [ ] (new-modern-theme) YENİ TASARIM (w/ Tailwind CSS)
- [ ] (automation-for-maintenance-fee) Otomatik olarak tüm apartmanlara aidat ekleyen bir betik olsun. Bu betik belirlediğimiz tarihte çalışıp, o aynı aidatını tüm dairelere eklesin.
- [ ] (add-maintenance-fee-column-for-buildings) Her apartmanın aidat bilgisi farklı olacağından, apartman eklerken bu apartmanın aidat miktarını da yazabilelim.
- [ ] (authentication-for-residents) Daire sakini aidat ödemek için sisteme giriş yapabilmeli.
- [ ] (authorization-for-residents) Sisteme giriş yapabilen daire sakini sadece kendine özel sayfayı görebilmeli.
- [ ] (transactions-for-residents-dashboard) Daire sakini sayfasında yalnızca transaction listesi, listede action olarak "Öde" butonu yer alsın.
- [ ] (pay-any-transaction-for-residents) Daire sakini ödeme işlemini "Kredi Kartı" ile ödeyebilsin.
- [ ] (payment-notification-from-residents) Transaction listesinde "Ödeme Bildirimi Yap" butonu yer alsın. Kullanıcı banka havalesi ile ödemiş ise, bu yöntemi kullansın.
- [ ] (payment-notification-for-admin-with-ajax) Daire sakini "Ödeme Bildirimi" gönderdiğinde, ajax request yardımı ile Admin ekranında notification çıksın.
- [ ] (payment-notification-for-admin-with-socket) Daire sakini "Ödeme Bildirimi" gönderdiğinde, socket.io yardımı ile Admin ekranında notification çıksın.
- [ ] (email-notifications-when-adding-an-expense) Herhangi bir borç eklendiğinde daire sakini eposta alsın.
- [ ] (email-notifications-when-payment-done) Herhangi bir borç ödendiğinde ya da ödeme bildirimi işleme alındığında daire sakini eposta alsın.
- [ ] (email-notifications-for-admins) Admin tüm bu epostaların birer kopyasını alsın.

### SİZDEN GELENLER
Eğer bir talebiniz varsa lütfen katkıda bulunarak buraya ekleme yapın ve pr gönderin. Canlı yayınlarda sizin istediklerinizi de projemize kodlayarak ekleyelim.
- [ ] ([fatihemre](https://github.com/fatihemre)) - Örnek talep satırı.

### TAMAMLANANLAR
- [x] ~~([#stats-on-dashboard](https://github.com/fatihemre/apteasy/tree/stats-on-dashboard)) Anasayfada toplam apartman, toplam daire, toplam yaşayan kişi ve toplam açık bakiye bilgileri kutu şeklinde görünür olsun.~~
- [x] ~~([profile-page](https://github.com/fatihemre/apteasy/tree/profile-page)) Giriş yapan kullanıcı için bir profil sayfası ekleyelim. Bu profil sayfasında kullanıcılar kendi bilgilerini güncelleyebilsinler.~~
- [x] ~~([localization](https://github.com/fatihemre/apteasy/tree/localization)) Birden fazla dil ekleyebilelim.~~
- [x] ~~([theme-support](https://github.com/fatihemre/apteasy/tree/theme-support)) Sisteme TEMA desteği ekleyelim.~~
- [x] ~~([2fa-for-users](https://github.com/fatihemre/apteasy/tree/2fa-for-users)) Kullanıcı 2FA ile giriş etkinleştirebilsin.~~