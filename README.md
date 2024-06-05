# IP-Adress-Manager

![image](https://github.com/ugurcomptech/IP-Adress-Manager/assets/133202238/97d12976-d477-4f28-aac2-5631b87abff8)

Bu basit PHP uygulaması, kullanıcının belirli IP adreslerini ekleyebileceği, düzenleyebileceği ve silebileceği bir arayüz sunar. Uygulama, bir veritabanı dosyasında IP adreslerini ve diğer verileri saklar.

## Kurulum

1. Bu projeyi klonlayın veya indirin.
2. Bir web sunucusunda veya yerel bir sunucuda proje dizinine erişim sağlayın.

## Kullanım

1. Ana sayfada, "Yeni IP Adresi Ekle" formunu kullanarak bir IP adresi ekleyin.
2. Eklenen IP adresleri listesi altında, her bir IP adresinin yanında bir düzenleme butonu bulunur. Bu düzenleme butonuna tıklayarak o IP adresini düzenleyebilirsiniz.
3. Aynı listede, her IP adresinin yanında bir seçim kutusu bulunur. Bu kutuları kullanarak birden fazla IP adresini seçip "Sil" butonuna tıklayarak seçili IP adreslerini silebilirsiniz.

# IP Adresi Yönetimi

IP Adresi Yönetimi, bir web tabanlı uygulamadır ve kullanıcıların IP adreslerini eklemelerine, düzenlemelerine ve silebilmelerine olanak tanır. Ayrıca IP adreslerinin eklenme süresini belirleyerek, belirli bir süre sonunda otomatik olarak silinmelerini sağlar.

## Özellikler

### 1. IP Ekleme

Kullanıcılar yeni bir IP adresi ekleyebilirler. Eklenen IP adresi, belirli bir süre boyunca sistemde tutulur ve sürenin sonunda otomatik olarak silinir.

### 2. IP Düzenleme

Var olan bir IP adresini düzenlemek isteyen kullanıcılar, mevcut IP adresini yeni bir IP adresi ile değiştirebilirler. Bu işlem doğrultusunda, IP adresi güncellenecektir.

### 3. IP Silme

Kullanıcılar, sistemdeki belirli IP adreslerini seçerek silebilirler. Bu işlem sonunda, seçilen IP adresleri sistemden kaldırılır.

### 4. Süresi Dolan IP Adreslerinin Otomatik Silinmesi

Sistem, herhangi bir IP adresinin eklenme süresinin dolup dolmadığını kontrol eder. Süresi dolmuş olan IP adresleri, otomatik olarak veritabanından silinir.

### 5. Kalan Sürenin Gösterilmesi

Her IP adresi için, eklenme süresinin dolmasına kadar kalan süre tabloda gösterilir. Bu sayede kullanıcılar, IP adreslerinin ne zaman silineceğini takip edebilirler.

**Not:** Süreyi "0" olarak yazarsanız süresiz olarak belirler.

## MYSQL 

MYSQL Sunucunuza giderek vey PhpMyadmin tarafından bir Database oluşturunuz. Sunucunuza aşağıdaki komutu yazarak bu işlemi sağlayabilirsiniz.

```mysql
CREATE DATABASE ip_management; USE ip_management; CREATE TABLE ips (id INT AUTO_INCREMENT PRIMARY KEY, ip_address VARCHAR(45) NOT NULL);
```

Eğer MySQL sadece Lokalde çalışıyor ise `nano /etc/mysql/mariadb.conf.d/50-server.cnf ` dosya yoluna giderek `bind-address=0.0.0.0` olarak güncelleyiniz.
```
root@ubuntu:~# netstat -tuln | grep 3306
tcp        0      0 127.0.0.1:3306          0.0.0.0:*               LISTEN     
```

Web Sitenizde görüntülemeye çalışınca aşağıdaki gibi bir hata almanız olası bir durumdur. Eğer böyle bir hata alısanız aşağıdaki belirtmiş olunan kodu yazmanız yeterlidir.
```
ERROR: Could not connect. SQLSTATE[HY000] [1044] Access denied for user 'user_name'@'%' to database 'database_name' 
```
```mysql
GRANT ALL PRIVILEGES ON database_name.* TO 'user_name'@'%';
FLUSH PRIVILEGES;
```


Eklenmiş olunan yeni 2 özellik için bir kaç kod daha yazmamız gerekecek. 

IP adresinin süresinin bitimini göstermek için MySql de aşağıdaki komutu çalıştırmanız gerekecek.

```mysql
 ALTER TABLE ips ADD creation_date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP, ADD expiry_seconds INT UNSIGNED NOT NULL DEFAULT 0;
```

Süre tutma özelliği için aşağıdaki komutu çalıştırın.

```mysql
ALTER TABLE ips ADD COLUMN expiry_date DATETIME;
```


## Güvenlik

- IP adresleri ve diğer veriler, MYSQL veritabanında saklanır.
- Uygulama, giriş doğrulaması veya IP adresi doğrulaması sağlamaz. Bu nedenle, uygulamayı kullanırken dikkatli olunmalıdır.

## Katkıda Bulunma

Eğer bu projeye katkıda bulunmak istiyorsanız, lütfen bir çekme isteği gönderin. Katkılarınızı memnuniyetle karşılayacağız!


## Lisans

[![License: CC BY-NC-SA 4.0](https://licensebuttons.net/l/by-nc-sa/4.0/88x31.png)](https://creativecommons.org/licenses/by-nc-sa/4.0/legalcode)

Bu projeyi [Creative Commons Attribution-NonCommercial-ShareAlike 4.0 International License](https://creativecommons.org/licenses/by-nc-sa/4.0/legalcode) altında lisansladık. Lisansın tam açıklamasını [CC BY-NC-SA 4.0](https://creativecommons.org/licenses/by-nc-sa/4.0/legalcode) sayfasında bulabilirsiniz.
