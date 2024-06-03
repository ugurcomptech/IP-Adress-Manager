# IP-Adress-Manager

![image](https://github.com/ugurcomptech/IP-Adress-Manager/assets/133202238/d6651775-8e3c-4f49-9c11-47201c6affde)


Bu basit PHP uygulaması, kullanıcının belirli IP adreslerini ekleyebileceği, düzenleyebileceği ve silebileceği bir arayüz sunar. Uygulama, bir dosyaya IP adreslerini kaydeder ve bu dosyadan IP adreslerini okur.

## Nasıl Çalışır

Uygulama, PHP ile yazılmıştır ve basit bir HTML formu aracılığıyla IP adreslerinin eklenmesini, düzenlenmesini ve silinmesini sağlar. 

- `index.php`: Ana uygulama dosyasıdır. Burada, ekli olan IP adreslerini görüntüleyebilir, yeni IP adresi ekleyebilir, varolan IP adreslerini düzenleyebilir ve silebilirsiniz.
- `allowed_ips.txt`: Bu dosya, ekli olan IP adreslerini saklar. Her satır bir IP adresini temsil eder.

## Kurulum

1. Bu projeyi klonlayın veya indirin.
2. Bir web sunucusunda veya yerel bir sunucuda proje dizinine erişim sağlayın.
3. PHP desteğine sahip bir sunucuda, projenin dizininde `index.php` dosyasını çalıştırarak uygulamayı başlatın.
4. `allowed_ips.txt` dosyasının yazılabilir olduğundan emin olun.

## Kullanım

1. Ana sayfada, "Yeni IP Adresi Ekle" formunu kullanarak bir IP adresi ekleyin.
2. Eklenen IP adresleri listesi altında, her bir IP adresinin yanında bir düzenleme butonu bulunur. Bu düzenleme butonuna tıklayarak o IP adresini düzenleyebilirsiniz.
3. Aynı listede, her IP adresinin yanında bir seçim kutusu bulunur. Bu kutuları kullanarak birden fazla IP adresini seçip "Sil" butonuna tıklayarak seçili IP adreslerini silebilirsiniz.

## Önemli Notlar

- Uygulama, güvenlik açısından giriş doğrulaması veya IP adresi doğrulaması sağlamaz. Bu nedenle, uygulamayı kullanırken dikkatli olunmalıdır.
- `allowed_ips.txt` dosyasının izinleri düzgün bir şekilde ayarlanmalıdır ve kötü niyetli kullanıcıların erişimini engellemek için gerekli önlemler alınmalıdır.

## Katkıda Bulunma

Eğer bu projeye katkıda bulunmak istiyorsanız, lütfen bir çekme isteği gönderin. Katkılarınızı memnuniyetle karşılayacağız!

## Lisans

Bu proje MIT lisansı altında lisanslanmıştır. Daha fazla bilgi için LICENSE dosyasına başvurun.
