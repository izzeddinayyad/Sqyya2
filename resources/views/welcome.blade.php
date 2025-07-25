<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>الصفحة الرئيسية - SqyyaAlmiyah</title>

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Cairo:wght@200..1000&family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap"
      rel="stylesheet"
    />

    <link rel="icon" href="{{ asset('images/favicon.ico') }}" />
    <link rel="stylesheet" href="{{ asset('css/style.css') }}" />
    <style>
      html { scroll-behavior: smooth; }
    </style>
</head>
<body>
    <!-- header section -->
    <header>
      <div class="container">
        <div class="logo">
          <img src="{{ asset('images/logo.png') }}" alt="شعار الشركة" />
        </div>
        <nav>
          <ul>
            <li><a href="#about">عن الشركة</a></li>
            <li><a href="#services">الخدمات</a></li>
            <li><a href="#contact">اتصل بنا</a></li>
            <li><a href="#who-we-are">من نحن</a></li>
          </ul>
        </nav>
        <div class="btn-group">
          <a href="{{ route('register') }}" class="register-btn">إنشاء حساب</a>
          <a href="{{ route('login') }}" class="login-btn">تسجيل الدخول</a>
        </div>
      </div>
    </header>
    <!-- end header section -->

    <!-- hero section -->
    <div class="hero-section">
      <div class="hero-content">
        <div class="title">
          <h2>سقيا المياه</h2>
          <h2>إدارة خدمات المياه في غزة</h2>
          <p>
            نوفر حلولاً مستدامة لإدارة المياه في ظل الظروف الصعبة لضمان وصول
            المياه النظيفة لجميع المواطنين
          </p>
        </div>
      </div>
    </div>
    <!-- end hero section -->

    <!-- about us section -->
    <div class="about-section container" id="about">
      <div class="divider">
        <h2 class="title-section">عن الشركة</h2>
        <img src="{{ asset('images/Vector.png') }}" alt="Vector" />
      </div>
      <div class="flex">
        <div class="content-section">
          <h3>رؤيتنا</h3>
          <div class="line"></div>
          <p>
            "سقيا المياه" هي مؤسسة وطنية تعمل في قطاع غزة لتوفير خدمات إدارة
            المياه في ظل الظروف الراهنة الصعبة. تأسست في عام 2023 استجابةً
            للأزمة المائية المتفاقمة في القطاع. تهدف المؤسسة إلى تحسين توزيع
            المياه باستخدام حلول مبتكرة، مع التركيز على تلبية احتياجات المجتمعات
            المحلية وتوفير المياه النقية للمناطق الأكثر احتياجًا.
          </p>
          <h3>رسالتنا</h3>
          <div class="line"></div>
          <p>
            "سقيا المياه" هي مؤسسة وطنية تعمل في قطاع غزة بهدف توفير خدمات إدارة
            المياه في ظل الظروف الراهنة الصعبة التي يمر بها القطاع. تأسست الشركة
            في عام 2023 استجابةً للأزمة المائية المتفاقمة في غزة نتيجة شح
            الموارد المائية وتزايد احتياجات السكان. تسعى "سقيا المياه" إلى تقديم
            حلول مبتكرة ومستدامة لتحسين توزيع المياه، وتلبية احتياجات المجتمعات
            المحلية المتضررة، مع التركيز على تحقيق العدالة في توزيع الموارد
            المائية وضمان وصول المياه النقية إلى جميع المناطق.
          </p>
        </div>
        <div class="image-section">
          <img src="{{ asset('images/about-section.png') }}" class="one" alt="عن الشركة" />
          <div class="two"></div>
        </div>
      </div>
    </div>
    <!-- end about us section -->

    <!-- service section -->
    <div class="service-section" id="services">
      <div class="container">
        <div class="divider">
          <h2 class="title-section">خدماتنا</h2>
          <img src="{{ asset('images/Vector.png') }}" alt="Vector" />
        </div>
        <div class="cards flex">
          <div class="card flex">
            <div class="image-card">
              <img src="{{ asset('images/photo_2025-04-23_22-10-27.jpg') }}" alt="تنقية المياه" />
            </div>
            <h2 class="title-card">تنقية المياه</h2>
            <p class="dec-card">
              نوفر وحدات تنقية مياه محمولة للمناطق التي تعاني من تلوث مصادر
              المياه , لضمان مياه شرب امنة
            </p>
          </div>
          <div class="card flex">
            <div class="image-card">
              <img src="{{ asset('images/photo_2025-04-23_21-54-12.jpg') }}" alt="صيانة الشبكات" />
            </div>
            <h2 class="title-card">صيانة الشبكات</h2>
            <p class="dec-card">
              نقدم خدمات صيانة طارئة للشبكات نوفر وحدات تنقية مياه محمولة
              للمناطق المائية المتضررة بسبب الظروف الحالية، مع فرق عمل مدربة على
              العمل في الظروف الصعبة.
            </p>
          </div>
          <div class="card flex">
            <div class="image-card">
              <img src="{{ asset('images/photo_2025-04-23_22-10-12.jpg') }}" alt="توزيع المياه" />
            </div>
            <h2 class="title-card">توزيع المياه</h2>
            <p class="dec-card">
              نقوم بتوزيع المياه النظيفة بشكل عادل عد المناطق، الأكثر احتياجاً
              فى غزة، مع إعطاء الأولوية للمستشفيات، المدارس، ومنازل النازحين.
            </p>
          </div>
          <div class="card flex">
            <div class="image-card">
              <img src="{{ asset('images/photo_2025-04-23_22-10-39.jpg') }}" alt="الاستجابة للطوارئ" />
            </div>
            <h2 class="title-card">الاستجابة للطوارئ</h2>
            <p class="dec-card">
              فرع طوارئ يعمل على مدار الساعة للاستجابة لأي أزمة مائية طارئة في
              أي منطقة من غزة.
            </p>
          </div>
          <div class="card flex">
            <div class="image-card">
              <img src="{{ asset('images/photo_2025-04-23_22-10-34.jpg') }}" alt="توفير خزانات مياه" />
            </div>
            <h2 class="title-card">توفير خزانات مياه</h2>
            <p class="dec-card">
              نوزع خزانات مياه على العائلات النازحة لترشيد استهلاك المياه والمحتاجة لتخزين المياه بشكل آمن.
            </p>
          </div>
          <div class="card flex">
            <div class="image-card">
              <img src="{{ asset('images/photo_2025-04-23_22-10-31.jpg') }}" alt="التوعية المائية" />
            </div>
            <h2 class="title-card">التوعية المائية</h2>
            <p class="dec-card">
              برامج التوعية المائية لترشيد استهلاك المياه وإعادة استخدامها في ظل شح الموارد.
            </p>
          </div>
        </div>
      </div>
    </div>
    <!-- end service section -->

    <!-- who we are -->
    <div class="about-section who-we-are-section container" id="who-we-are">
      <div class="divider">
        <h2 class="title-section">من نحن</h2>
        <img src="{{ asset('images/Vector.png') }}" alt="Vector" />
      </div>
      <div class="flex">
        <div class="image-section">
          <img src="{{ asset('images/about-section.png') }}" class="one-1" alt="من نحن" />
          <div class="two-1"></div>
        </div>
        <div class="content-section">
          <h3>من نحن</h3>
          <p>
            سقيا المياه هي فريق من المهندسين والخبراء المحليين الذين يعملون بجد
            لتوفير حلول مائية مستدامة لسكان غزة. نؤمن بأن الوصول إلى المياه
            النظيفة هو حق أساسي لكل إنسان.
          </p>
          <ul>
            <li>مهندسي مياه وخبراء في إدارة الموارد المائية</li>
            <li>فني صيانة وتوزيع مياه مدربين</li>
            <li>فريق استجابة سريعة للطوارئ</li>
            <li>متطوعين من المجتمع الدولي</li>
          </ul>
          <p>
            سقيا المياه هي فريق من المهندسين والخبراء المحليين الذين يعملون بجد
            لتوفير حلول مائية مستدامة لسكان غزة. نؤمن بأن الوصول إلى المياه
            النظيفة هو حق أساسي لكل إنسان.
          </p>
        </div>
      </div>
    </div>
    <!-- end who we are section -->

    <!-- contact us section -->
    <div class="contact-section" id="contact">
      <div class="container">
        <div class="divider">
          <h2 class="title-section">اتصل بنا</h2>
          <img src="{{ asset('images/Vector.png') }}" alt="Vector" />
        </div>
        <div class="contact-form flex">
          <div class="form-section">
            <form action="#" method="POST" class="flex">
              @csrf
              <div class="name">
                <label for="name">الاسم</label>
                <input
                  type="text"
                  name="name"
                  id="name"
                  placeholder="ادخل الاسم"
                  required
                />
              </div>
              <div class="phone-email flex">
                <div class="phone flex">
                  <label for="phone">رقم الهاتف</label>
                  <input
                    type="tel"
                    name="phone"
                    id="phone"
                    placeholder="أدخل الهاتف"
                    required
                  />
                </div>
                <div class="email">
                  <label for="email">البريد الإلكتروني</label>
                  <input
                    type="email"
                    name="email"
                    id="email"
                    placeholder="أدخل البريد الإلكتروني"
                    required
                  />
                </div>
              </div>
              <div class="message">
                <label for="message">الرسالة</label>
                <textarea
                  name="message"
                  id="message"
                  placeholder="الرسالة"
                  rows="7"
                  required
                ></textarea>
              </div>
              <button type="submit">إرسال</button>
            </form>
          </div>
          <div class="phone-section">
            <div class="phone flex">
              <div class="icons">
                <img src="{{ asset('images/Frame (1).png') }}" alt="هاتف" />
              </div>
              <div class="tr">
                <span>جوال</span>
                <span>0594567890</span>
              </div>
            </div>
            <div class="email flex">
              <div class="icons">
                <img src="{{ asset('images/Frame.png') }}" alt="بريد إلكتروني" />
              </div>
              <div class="tr">
                <span>البريد الإلكتروني</span>
                <span>SqyyaAlmiyah@gmail.com</span>
              </div>
            </div>
            <div class="adress flex">
              <div class="icons">
                <img src="{{ asset('images/location-01.png') }}" alt="موقع" />
              </div>
              <div class="tr">
                <span>المقر الرئيسي</span>
                <span>دير البلح - دوار المدفع</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- end contact section -->

    <!-- footer section -->
    <footer>
      <div class="container">
        <h2>شركة شحن المياه | جميع الحقوق محفوظة 2025</h2>
        <div class="footer-links">
          <a href="#">Facebook |</a>
          <a href="#">Instagram |</a>
          <a href="#">Twitter |</a>
          <a href="#">YouTube</a>
        </div>
        <span class="footer-terms">الشروط والأحكام | سياسة الخصوصية</span>
      </div>
    </footer>
    <!-- end footer section -->
</body>
</html>
