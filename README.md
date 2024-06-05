# IP-Adress-Manager

![image](https://github.com/ugurcomptech/IP-Adress-Manager/assets/133202238/d6651775-8e3c-4f49-9c11-47201c6affde)

Bu basit PHP uygulaması, kullanıcının belirli IP adreslerini ekleyebileceği, düzenleyebileceği ve silebileceği bir arayüz sunar. Uygulama, bir JSON dosyasında IP adreslerini ve diğer verileri saklar.

## Kurulum

1. Bu projeyi klonlayın veya indirin.
2. Bir web sunucusunda veya yerel bir sunucuda proje dizinine erişim sağlayın.
3. `allowed_ips.json` dosyasının yazılabilir olduğundan emin olun.

## Kullanım

1. Ana sayfada, "Yeni IP Adresi Ekle" formunu kullanarak bir IP adresi ekleyin.
2. Eklenen IP adresleri listesi altında, her bir IP adresinin yanında bir düzenleme butonu bulunur. Bu düzenleme butonuna tıklayarak o IP adresini düzenleyebilirsiniz.
3. Aynı listede, her IP adresinin yanında bir seçim kutusu bulunur. Bu kutuları kullanarak birden fazla IP adresini seçip "Sil" butonuna tıklayarak seçili IP adreslerini silebilirsiniz.

## Güvenlik

- IP adresleri ve diğer veriler, MYSQL veritabanında saklanır.
- Uygulama, giriş doğrulaması veya IP adresi doğrulaması sağlamaz. Bu nedenle, uygulamayı kullanırken dikkatli olunmalıdır.

## Katkıda Bulunma

Eğer bu projeye katkıda bulunmak istiyorsanız, lütfen bir çekme isteği gönderin. Katkılarınızı memnuniyetle karşılayacağız!


## Lisans

[![License: CC BY-NC-SA 4.0](https://licensebuttons.net/l/by-nc-sa/4.0/88x31.png)](https://creativecommons.org/licenses/by-nc-sa/4.0/legalcode)

Bu projeyi [Creative Commons Attribution-NonCommercial-ShareAlike 4.0 International License](https://creativecommons.org/licenses/by-nc-sa/4.0/legalcode) altında lisansladık. Lisansın tam açıklamasını [CC BY-NC-SA 4.0](https://creativecommons.org/licenses/by-nc-sa/4.0/legalcode) sayfasında bulabilirsiniz.
