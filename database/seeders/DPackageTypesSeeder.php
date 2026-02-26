<?php

namespace Database\Seeders;

use App\Models\Dictionaries\PackageType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DPackageTypesSeeder extends Seeder
{
    public function run(): void
    {
        $raw_uk = <<<TXT
        43 - Мішок великий, для товарів великого розміру, навалом
        44 - Мішок поліетиленовий
        1A - Барабан сталевий
        1B - Барабан з алюмінію
        1D - Барабан з фанери
        1F - Контейнер, гнучкий
        1G - Барабан фібровий
        1W - Барабан з дерева
        2C - Бочка дерев'яна
        3A - Каністра сталева
        3H - Каністра з пластику
        4A - Коробка сталева
        4B - Коробка алюмінієва
        4C - Коробка з натуральної деревини
        4D - Коробка з фанери
        4F - Коробка з деревинного матеріалу
        4G - Коробка з фібрового картону
        4H - Коробка з пластику
        5H - Мішок з пластикової тканини
        5L - Мішок текстильний
        5M - Мішок паперовий
        6H - Комбіноване пакування, пластикова ємність
        6P - Комбіноване пакування, скляна ємність
        7A - Ящик автомобільний
        7B - Ящик дерев'яний
        8A - Піддон/палет дерев'яний
        8B - Ящик решітчастий, дерев'яний
        8C - Зв'язка дерев'яна
        AA - Контейнер середньої вантажопідйомності для масових вантажів з твердого пластику
        AB - Посудина з волокна
        AC - Посудина паперова
        AD - Посудина дерев'яна
        AE - Аерозольний балон
        AF - Піддон/палет модульний з обичайкою розміром 80 см на 60 см
        AG - Піддон/палет у термоусадочній плівці
        AH - Піддон/палет розміром 100 см на 110 см
        AI - Грейферний ковш
        AJ - Кульок
        AL - Куля
        AM - Ампула незахищена
        AP - Ампула захищена
        AT - Пульверизатор
        AV - Капсула
        B4 - Стрічка
        BA - Бочка/барель
        BB - Бобіна
        BC - Решітчастий ящик/підставка для пляшок
        BD - Дошка
        BE - Зв'язка
        BF - Балон незахищений
        BG - Мішок
        BH - Пучок
        BI - Бункер
        BJ - Баддя, цебро
        BK - Кошик
        BL - Стос компактний
        BM - Таз
        BN - Стос некомпактний
        BO - Пляшка незахищена, циліндрична
        BP - Балон захищений
        BQ - Пляшка захищена, циліндрична
        BR - Брусок
        BS - Пляшка незахищена, опукла
        BT - Рулон
        BU - Діжка пивна
        BV - Пляшка захищена, опукла
        BW - Коробка для рідин
        BX - Коробка
        BY - Дошка у зв'язці/пакеті/штабелі
        BZ - Бруски у зв'язці/пакеті/штабелі
        CA - Банка прямокутна
        CB - Пивний ящик
        CC - Фляга
        CD - Жерстяна банка з ручкою та випускним отвором
        CE - Рибацький кошик великий плетений
        CF - Кофр
        CG - Клітка
        CH - Скриня
        CI - Каністра
        CJ - Труна
        CK - Діжка середня пивна
        CL - Бухта (моток)
        CM - Кардна стрічка
        CN - Контейнер, не віднесений до переліченого транспортувального обладнання
        CO - Сулія оплетена, незахищена
        CP - Сулія оплетена, захищена
        CQ - Картридж-касета
        CR - Ящик решітчастий (кліть)
        CS - Ящик
        CT - Коробка картонна
        CU - Чашка
        CV - Футляр
        CW - Клітка з вальцями
        CX - Банка циліндрична
        CY - Циліндр
        CZ - Брезент
        DA - Ящик решітчастий, багатошаровий пластиковий
        DB - Ящик решітчастий, багатошаровий, з дерева
        DC - Ящик решітчастий багатошаровий, картонний
        DG - Клітка, Загальний фонд транспортувального обладнання
        DH - Коробка (багатообертова) з Загального фонду транспортувального обладнання ЄС, Єврокоробка
        DI - Барабан залізний
        DJ - Деміджон оплетений, незахищений
        DK - Ящик решітчастий, для масових вантажів, з картону
        DL - Ящик решітчастий, для масових вантажів, пластиковий
        DM - Ящик решітчастий, для масових вантажів, з дерева
        DN - Дозатор
        DP - Деміджон оплетений, захищений
        DR - Барабан
        DS - Лоток одношаровий без покриття, пластиковий
        DT - Лоток одношаровий без покриття, дерев'яний
        DU - Лоток одношаровий без покриття, з полістиролу
        DV - Лоток одношаровий без покриття, картонний
        DW - Лоток двошаровий без покриття, пластиковий
        DX - Лоток двошаровий без покриття, дерев'яний
        DY - Лоток двошаровий без покриття, картонний
        EC - Мішок пластиковий
        ED - Ящик з піддоном
        EE - Ящик з піддоном, дерев'яний
        EF - Ящик з піддоном, картонний
        EG - Ящик з піддоном, пластиковий
        EH - Ящик з піддоном, металевий
        EI - Ящик ізотермічний
        EN - Конверт
        FB - М'який мішок
        FC - Ящик решітчатий для фруктів
        FD - Рама каркасу
        FE - М'який мішок, гнучка цистерна
        FI - Бочка мала
        FL - Колба
        FO - Рундук
        FP - Касета для плівки
        FR - Каркас
        FT - Контейнер продуктовий
        FW - Візок, безбортовий
        FX - Мішок, гнучкий контейнер
        GB - Газовий балон
        GI - Брус
        GL - Контейнер, галон
        GR - Резервуар скляний
        GU - Лоток з плоскими горізонтально вкладеними елементами
        GY - Мішок з мішковини
        GZ - Бруси у зв'язці/пакеті/штабелі
        HA - Корзина з ручками, пластикова
        HB - Корзина з ручками, дерев'яна
        HC - Корзина з ручками, картонна
        HG - Бочка велика
        HN - Гак
        HR - Кошик з кришкою
        IA - Пакунок демонстраційний, дерев'яний
        IB - Пакунок демонстраційний, картонний
        IC - Пакунок демонстраційний, пластиковий
        ID - Пакунок демонстраційний, металевий
        IE - Пакунок виставковий
        IF - Пакунок, випресований
        IG - Пакунок у загортальному папері
        IH - Барабан з пластику
        IK - Пакунок з отворами для пляшок картонний
        IL - Жорсткий лоток з кришкою та перегородками (CEN TS 14482:2002)
        IN - Болванка
        IZ - Болванки у зв'язці/пакеті/штабелі
        JB - Мішок великий
        JC - Каністра прямокутна
        JG - Глек
        JR - Банка з широкою горловиною (ємністю приблизно 4,5 л)
        JT - Мішок джутовий
        JY - Каністра циліндрична
        KG - Кег
        KI - Набір
        LE - Багаж
        LG - Колода
        LT - Партія вантажу
        LU - Лаг (дерев'яний ящик)
        LV - Ліфт ван (дерев'яний короб розміром приблизно 220 см (довжина) х 115 см (ширина) х 220 см (висота))
        LZ - Колода у зв'язці/пакеті/штабелі
        MA - Ящик решітчастий, металевий
        MB - Мішок багатошаровий
        MC - Ящик решітчастий для молочних пляшок
        ME - Контейнер металевий
        MR - Резервуар металевий
        MS - Лантух багатошаровий
        MT - Мішок рогожаний
        MW - Резервуар з пластиковим покриттям
        MX - Коробка сірників
        NA - Немає відомостей
        NE - Не пакований чи не розфасований
        NF - Не пакований чи не розфасований одиничний вантаж
        NG - Не пакований чи не розфасований багатомісний вантаж
        NS - Гніздо (комірка)
        NT - Сітка
        NU - Сітка трубчаста пластикова
        NV - Сітка трубчаста текстильна
        OA - Піддон/палет, розміром 40 см на 60 см
        OB - Піддон/палет, розміром 80 см на 120 см
        OC - Піддон/палет, розміром 100 см на 120 см
        OD - Піддон/палет, AS 4068-1993
        OE - Піддон/палет, ISO T11
        OF - Платформа невизначеної ваги та розміру
        OK - Блок
        OT - Октабін
        OU - Контейнер зовнішній
        P2 - Лоток
        PA - Пакет
        PB - Піддон/палет ящик
        PC - Посилка
        PD - Піддон/палет модульний з обичайкою розміром 80 см на 100 см
        PE - Піддон/палет модульний з обичайкою розміром 80 см на 120 см
        PF - Загін для худоби
        PG - Плита
        PH - Глек великий
        PI - Труба
        PJ - Квадратна/прямокутна для ягід/фруктів корзина зі шпону
        PK - Пакунок
        PL - Відро
        PN - Дошка товста
        PO - Сумка
        PP - Штука
        PR - Резервуар пластиковий
        PT - Горщик
        PU - Лоток
        PV - Труби у зв'язці/пакеті/штабелі
        PX - Піддон/палет
        PY - Плити у зв'язці/пакеті/штабелі
        PZ - Дошка товста у зв'язці/пакеті/штабелі
        QA - Барабан сталевий з нез'ємним днищем
        QB - Барабан сталевий зі з'ємним днищем
        QC - Барабан з алюмінію з нез'ємним днищем
        QD - Барабан з алюмінію зі з'ємним днищем
        QF - Барабан з пластику з нез'ємним днищем
        QG - Барабан з пластику зі з'ємним днищем
        QH - Бочка дерев'яна з пробкою
        QJ - Бочка дерев'яна зі з'ємним днищем
        QK - Каністра сталева з нез'ємним днищем
        QL - Каністра зі з'ємним днищем
        QM - Каністра з пластику з нез'ємним днищем
        QN - Каністра з пластику зі з'ємним днищем
        QP - Коробка з натуральної деревини, звичайна
        QQ - Коробка з натуральної деревини з щільними стінками
        QR - Коробка з пінопласту
        QS - Коробка з твердого пластику
        RD - Пруток
        RG - Кільце
        RJ - Стійка, вішалка для одягу
        RK - Стійка
        RL - Катушка
        RO - Згорток
        RT - Сітка "Реднет"
        RZ - Прутки у зв'язці/пакеті/штабелі
        SA - Лантух
        SB - Сляб
        SC - Ящик решітчастий, мілкий
        SD - Веретено
        SE - Кінгстонна коробка
        SH - Пакет малий
        SI - Стелаж
        SK - Ящик каркасний
        SL - Лист прокладний
        SM - Лист металевий
        SO - Шпулька
        SP - Лист з пластиковим покриттям
        SS - Ящик сталевий
        ST - Аркуш (лист)
        SU - Валіза
        SV - Обгортка сталева
        SW - У термоусадочній плівці
        SX - Комплект
        SY - Гільза
        SZ - Листи у зв'язці/пакеті/штабелі
        T1 - Пігулка
        TB - Діжка
        TC - Чайна коробка
        TD - Розбірна трубка чи туба
        TE - Шина
        TG - Цистерна універсальна
        TI - Діжка дерев'яна
        TK - Бак прямокутний
        TL - Діжка з кришкою
        TN - Бляшанка
        TO - Бочка для вина чи пива, велика
        TR - Дорожня скриня
        TS - Жмуток, зв'язка
        TT - Мішок
        TU - Тюбик
        TV - Тюбик з насадкою
        TW - Піддон/палет, тришаровий
        TY - Бак циліндричний
        TZ - Трубки чи туби у зв'язці/пакеті/штабелі
        UC - Без кліті
        UN - Одиниця
        VA - Бак
        VG - Об'ємні перевезення, газ (при 1031 мбар та 15 C)
        VI - Флакон
        VK - Консоль для обладнання, пристосована для міні фургону
        VL - Об'ємні перевезення, рідина
        VN - Транспортний засіб
        VO - Об'ємні перевезення, великі частки ("кульки")
        VP - Вакуумна упаковка
        VQ - Об'ємні перевезення, зріджений газ (особливий режим температури та тиску)
        VR - Об'ємні перевезення, гранульовані частки ("зерно")
        VS - Навалом металобрухт
        VY - Об'ємні перевезення, дрібні частки ("порошок")
        WA - Контейнер середньої вантажопідйомності для масових вантажів
        WB - Пляшка оплетена
        WC - Контейнер середньої вантажопідйомності для масових вантажів, сталевий
        WD - Контейнер середньої вантажопідйомності для масових вантажів, алюмінієвий
        WF - Контейнер середньої вантажопідйомності для масових вантажів, металевий
        WG - Контейнер середньої вантажопідйомності для масових вантажів, сталевий, герметизований понад 10 КПа
        WH - Контейнер середньої вантажопідйомності для масових вантажів, алюмінієвий герметизований понад 10 КПа
        WJ - Контейнер середньої вантажопідйомності для масових вантажів, металевий, герметизований понад 10 КПа
        WK - Контейнер середньої вантажопідйомності для наливних вантажів, сталевий
        WL - Контейнер середньої вантажопідйомності для наливних вантажів, алюмінієвий
        WM - Контейнер середньої вантажопідйомності для наливних вантажів, металевий
        WN - Контейнер середньої вантажопідйомності для масових вантажів, з пластикової тканини без зовнішнього/внутрішнього обшиття
        WP - Контейнер середньої вантажопідйомності для масових вантажів, з пластикової тканини, з зовнішнім обшиттям
        WQ - Контейнер середньої вантажопідйомності для масових вантажів, з пластикової тканини, з внутрішнім обшиттям
        WR - Контейнер середньої вантажопідйомності для масових вантажів, з пластикової тканини, з зовнішнім та внутрішнім обшиттям
        WS - Контейнер середньої вантажопідйомності для масових вантажів, з пластикової плівки
        WT - Контейнер середньої вантажопідйомності для масових вантажів, текстильний без зовнішнього/внутрішнього обшиття
        WU - Контейнер середньої вантажопідйомності для масових вантажів, з натуральної деревини з внутрішнім обшиттям
        WV - Контейнер середньої вантажопідйомності для масових вантажів, текстильний з зовнішнім обшиттям
        WW - Контейнер середньої вантажопідйомності для масових вантажів, текстильний з внутрішнім обшиттям
        WX - Контейнер середньої вантажопідйомності для масових вантажів, текстильний з зовнішнім та внутрішнім обшиттям
        WY - Контейнер середньої вантажопідйомності для масових вантажів, фанерний з внутрішнім обшиттям
        WZ - Контейнер середньої вантажопідйомності для масових вантажів, з відтвореної деревини з внутрішнім обшиттям
        XA - Мішок з пластикової тканини, без внутрішнього покриття/вкладиша
        XB - Мішок з пластикової тканини, щільний
        XC - Мішок з пластикової тканини, водонепроникний
        XD - Мішок з пластикової плівки
        XF - Мішок текстильний, без внутрішнього покриття/вкладиша
        XG - Мішок текстильний, щільний
        XH - Мішок текстильний, водонепроникний
        XJ - Мішок паперовий, багатошаровий
        XK - Мішок паперовий, багатошаровий, водонепроникний
        YA - Комбіноване пакування, пластикова ємність у сталевому барабані
        YB - Комбіноване пакування, пластикова ємність у ящику решітчастому із сталі
        YC - Комбіноване пакування, пластикова ємність у барабані з алюмінію
        YD - Комбіноване пакування, пластикова ємність у решітчастому ящику з алюмінію
        YF - Комбіноване пакування, пластикова ємність у коробці з дерева
        YG - Комбіноване пакування, пластикова ємність у барабані з фанери
        YH - Комбіноване пакування, пластикова ємність у коробці з фанери
        YJ - Комбіноване пакування, пластикова ємність у фібровому барабані
        YK - Комбіноване пакування, пластикова ємність у коробці з фібрового картону
        YL - Комбіноване пакування, пластикова ємність у пластиковому барабані
        YM - Комбіноване пакування, пластикова ємність у коробці з твердої пластмаси
        YN - Комбіноване пакування, скляна ємність у барабані із сталі
        YP - Комбіноване пакування, скляна ємність у ящику решітчастому із сталі
        YQ - Комбіноване пакування, скляна ємність у барабані з алюмінію
        YR - Комбіноване пакування, скляна ємність у ящику решітчастому з алюмінію
        YS - Комбіноване пакування, скляна ємність у коробці дерев'яній
        YT - Комбіноване пакування, скляна ємність у барабані фанерному
        YV - Комбіноване пакування, скляна ємність у плетеному кошику
        YW - Комбіноване пакування, скляна ємність у барабані фібровому
        YX - Комбіноване пакування, скляна ємність у коробці з фібрового картону
        YY - Комбіноване пакування, скляна ємність у пінопластовому пакеті
        YZ - Комбіноване пакування, скляна ємність у пакеті з твердої пластмаси
        ZA - Контейнер середньої вантажопідйомності для масових вантажів, паперовий, багатошаровий
        ZB - Мішок великий
        ZC - Контейнер середньої вантажопідйомності для масових вантажів, паперовий, багатошаровий, водонепроникний
        ZD - Контейнер середньої вантажопідйомності для твердих навалювальних/насипних вантажів, з твердого пластику з структурним оснащенням
        ZF - Контейнер середньої вантажопідйомності для твердих навалювальних/насипних вантажів, з твердого пластику автономний
        ZG - Контейнер середньої вантажопідйомності для масових вантажів, з твердого пластику, з структурним оснащенням, герметизований
        ZH - Контейнер середньої вантажопідйомності для масових вантажів, з твердого пластику, автономний, герметизований
        ZJ - Контейнер середньої вантажопідйомності для наливних вантажів, з твердого пластику, з структурним оснащенням
        ZK - Контейнер середньої вантажопідйомності для наливних вантажів, з твердого пластику, автономний
        ZL - Контейнер середньої вантажопідйомності для твердих навалювальних/насипних вантажів, складний з твердого пластику
        ZM - Контейнер середньої вантажопідйомності для твердих навалювальних/насипних вантажів, складний з гнучкого пластику
        ZN - Контейнер середньої вантажопідйомності для масових вантажів, складний з твердого пластику, герметизований
        ZP - Контейнер середньої вантажопідйомності для наливних вантажів, складний з гнучкого пластику, герметизований
        ZQ - Контейнер середньої вантажопідйомності для наливних вантажів, складний з твердого пластику
        ZR - Контейнер середньої вантажопідйомності для наливних вантажів, з гнучкого пластику
        ZS - Контейнер середньої вантажопідйомності для масових вантажів, складний
        ZT - Контейнер середньої вантажопідйомності для масових вантажів, з фібрового картону
        ZU - Контейнер середньої вантажопідйомності для масових вантажів, гнучкий
        ZV - Контейнер середньої вантажопідйомності для наливних вантажів, металевий, окрім сталевих
        ZW - Контейнер середньої вантажопідйомності для масових вантажів, з натуральної деревини
        ZX - Контейнер середньої вантажопідйомності для масових вантажів, фанерний
        ZY - Контейнер середньої вантажопідйомності для масових вантажів, з відтвореної деревини
        ZZ - За взаємним визначенням
        TXT;


        $raw_en = <<<TXT
        43 - Bag, super bulk
        44 - Bag, polybag
        1A - Drum, steel
        1B - Drum, aluminium
        1D - Drum, plywood
        1F - Container, flexible
        1G - Drum, fibre
        1W - Drum, wooden
        2C - Barrel, wooden
        3A - Jerrycan, steel
        3H - Jerrycan, plastic
        4A - Box, steel
        4B - Box aluminium
        4C - Box, natural wood
        4D - Box, plywood
        4F - Box, reconstituted wood
        4G - Box, fibreboard
        4H - Box, plastic
        5H - Bag, woven plastic
        5L - Bag, textile
        5M - Bag, paper
        6H - Composite packaging, plastic receptacle
        6P - Composite packaging, glass receptacle
        7A - Case, car
        7B - Case, wooden
        8A - Pallet, wooden
        8B - Crate, wooden
        8C - Bundle, wooden
        AA - Intermediate bulk container, rigid plastic
        AB - Receptacle, fibre
        AC - Receptacle, paper
        AD - Receptacle, wooden
        AE - Aerosol
        AF - Pallet, modular, collars 80 cm х 60 cm
        AG - Pallet, shrink-wrapped
        AH - Pallet, 100 cm x 110 cm
        AI - Clamshell
        AJ - Cone
        AL - Ball
        AM - Ampoule, non-protected
        AP - Ampoule, protected
        AT - Atomizer
        AV - Capsule
        B4 - Belt
        BA - Barrel
        BB - Bobbin
        BC - Bottlecrate/bottlerack
        BD - Board
        BE - Bundle
        BF - Balloon, non-protected
        BG - Bag
        BH - Bunch
        BI - Bin
        BJ - Bucket
        BK - Basket
        BL - Bale, compressed
        BM - Basin
        BN - Bale, non-compressed
        BO - Bottle, non-protected, cylindrical
        BP - Balloon, protected
        BQ - Bottle, protected, cylindrical
        BR - Bar
        BS - Bottle, non-protected, bulbous
        BT - Bolt
        BU - Butt
        BV - Bottle, protected, bulbous
        BW - Box, for liquids
        BX - Box
        BY - Board, in bundle/bunch/truss
        BZ - Bars, in bundle/bunch/truss
        CA - Can, rectangular
        CB - Crate, beer
        CC - Churn
        CD - Can, with handle and spot
        CE - Creel
        CF - Coffer
        CG - Cage
        CH - Chest
        CI - Canister
        CJ - Coffin
        CK - Cask
        CL - Coil
        CM - Card
        CN - Container, not otherwise specified as transport equipment
        CO - Carboy, non-protected
        CP - Carboy, protected
        CQ - Cartridge
        CR - Crate
        CS - Case
        CT - Carton
        CU - Cup
        CV - Cover
        CW - Cage, roll
        CX - Can, cylindrical
        CY - Cylinder
        CZ - Canvas
        DA - Crate, multiple layer, plastic
        DB - Crate, multiple layer, wooden
        DC - Crate, multiple layer, cardboard
        DG - Cage, Commonwealth Handling Equipment Pool (CHEP)
        DH - Box, Commonwealth Handling Equipment Pool (CHEP) Eurobox
        DI - Drum, iron
        DJ - Demijohn, non-protected
        DK - Crate, bulk, cardboard
        DL - Crate, bulk, plastic
        DM - Crate, bulk, wooden
        DN - Dispenser
        DP - Demijohn protected
        DR - Drum
        DS - Tray, one layer no cover, plastic
        DT - Tray, one layer no cover, wooden
        DU - Tray, one layer no cover, polystyrene
        DV - Tray, one layer no cover, cardboard
        DW - Tray, two layers no cover, plastic tray
        DX - Tray, two layers no cover, wooden
        DY - Tray, two layers no cover, cardboard
        EC - Bag, plastics
        ED - Case, with pallet base
        EE - Case, with pallet base, wooden
        EF - Case, with pallet base, cardboard
        EG - Case, with pallet base, plastic
        EH - Case, with pallet base, metal
        EI - Case, isothermic
        EN - Envelope
        FB - Flexibag
        FC - Crate, fruit
        FD - Crate, framed
        FE - Flexitank, Flexibag
        FI - Firkin
        FL - Flask
        FO - Footlocker
        FP - Filmpack
        FR - Frame
        FT - Foodtainer
        FW - Cart, flatbed
        FX - Bag, flexible container
        GB - Bottle, gas
        GI - Girder
        GL - Container, gallon
        GR - Receptacle, glass
        GU - Tray, containing horizontally stacked flat items
        GY - Bag, gunny
        GZ - Girders, in bundle/bunch/truss
        HA - Basket, with handle, plastic
        HB - Basket, with handle, wooden
        HC - Basket, with handle, cardboard
        HG - Hogshead
        HN - Hanger
        HR - Hamper
        IA - Package, display, wooden
        IB - Package, display, cardboard
        IC - Package, display, plastic
        ID - Package, display, metal
        IE - Package, show
        IF - Package, flow
        IG - Package, paper wrapped
        IH - Drum, plastic
        IK - Package, cardboard, with bottle grip-holes
        IL - Tray, rigid, lidded stackable (CEN TS 14482:2002)
        IN - Ingot
        IZ - Ingots, in bundle/bunch/truss
        JB - Bag, jumbo
        JC - Jerrican, rectangular
        JG - Jug
        JR - Jar
        JT - Jute bag
        JY - Jerrican, cylindrical
        KG - Keg
        KI - Kit
        LE - Luggage
        LG - Log
        LT - Lot
        LU - Lug
        LV - Lift van
        LZ - Logs, in bundle/bunch/truss
        MA - Crate, metal
        MB - Bag, multiply
        MC - Crate, milk
        ME - Container, metal
        MR - Receptacle, metal
        MS - Sack, multi-wall
        MT - Mat
        MW - Receptacle, plastic wrapped
        MX - Matchbox
        NA - Not available
        NE - Unpacked or unpackaged
        NF - Unpacked or unpackaged, single unit
        NG - Unpacked or unpackaged, multiple units
        NS - Nest
        NT - Net
        NU - Net, tube, plastic
        NV - Net, tube, textile
        OA - Pallet, CHEP 40 cm x 60 cm
        OB - Pallet, CHEP 80 cm x120 cm
        OC - Pallet, CHEP 100 cm x120 cm
        OD - Pallet, AS 4068-1993
        OE - Pallet, ISO T11
        OF - Platform, unspecified weight or dimension
        OK - Block
        OT - Octabin
        OU - Container, outer
        P2 - Pan
        PA - Packet
        PB - Pallet, box
        PC - Parcel
        PD - Pallet, modular, collars 80 cm х 100 cm
        PE - Pallet, modular, collars 80 cm х 120 cm
        PF - Pen
        PG - Plate
        PH - Pitcher
        PI - Pipe
        PJ - Punnet
        PK - Package
        PL - Pail
        PN - Plank
        PO - Pouch
        PP - Piece
        PR - Receptacle, plastic
        PT - Pot
        PU - Tray
        PV - Pipes, in bundle/bunch/truss
        PX - Pallet
        PY - Plates, in bundle/bunch/truss
        PZ - Planks, in bundle/bunch/truss
        QA - Drum, steel, non-removable head
        QB - Drum, steel, removable head
        QC - Drum, aluminium, non-removable head
        QD - Drum, aluminium, removable head
        QF - Drum, plastic, non-removable head
        QG - Drum, plastic, removable head
        QH - Barrel, wooden, bung type
        QJ - Barrel, wooden, removable head
        QK - Jerrican, steel, non-removable head
        QL - Jerrican, steel, removable head
        QM - Jerrican, plastic, non-removable head
        QN - Jerrican, plastic, removable head
        QP - Box, wooden, natural wood, ordinary
        QQ - Box, wooden, natural wood, with soft proof walls
        QR - Box, plastic, expanded
        QS - Box, plastic, solid
        RD - Rod
        RG - Ring
        RJ - Rack, clothing hanger
        RK - Rack
        RL - Reel
        RO - Roll
        RT - Rednet
        RZ - Rods, in bundle/bunch/truss
        SA - Sack
        SB - Slab
        SC - Crate, shallow
        SD - Spindle
        SE - Sea-chest
        SH - Sachet
        SI - Skid
        SK - Case, skeleton
        SL - Slip-sheet
        SM - Sheet metal
        SO - Spool
        SP - Sheet, plastic wrapping
        SS - Case, steel
        ST - Sheet
        SU - Suitcase
        SV - Envelope, steel
        SW - Shrink-wrapped
        SX - Set
        SY - Sleeve
        SZ - Sheets, inbundle/bunch/truss
        T1 - Tablet
        TB - Tub
        TC - Tea-chest
        TD - Tube, collapsible
        TE - Tyre
        TG - Tank container, generic
        TI - Tierce
        TK - Tank, rectangular
        TL - Tub, with lid
        TN - Tin
        TO - Tun
        TR - Trunk
        TS - Truss
        TT - Bag, tote
        TU - Tube
        TV - Tube, with nozzle
        TW - Pallet, triwall
        TY - Tank, cylindrical
        TZ - Tubes, in bundle/bunch/truss
        UC - Uncaged
        UN - Unit
        VA - Vat
        VG - Bulk, gas (at 1031 mbar and 15 C)
        VI - Vial
        VK - Vanpack
        VL - Bulk, liquid
        VN - Vehicle
        VO - Bulk, solid, large particles (nodules)
        VP - Vacuum-packed
        VQ - Bulk, liquedfied gas (at abnormal temperature/pressure)
        VR - Bulk, solid, granular, particles (grains)
        VS - Bulk, scrap metal
        VY - Bulk, solid, fine particles (powders)
        WA - Intermediate bulk container
        WB - Wicker bottle
        WC - Intermediate bulk container, steel
        WD - Intermediate bulk container, aluminium
        WF - Intermediate bulk container, metal
        WG - Intermediate bulk container, steel, pressurised > 10 kpa
        WH - Intermediate bulk container, aluminium, pressurised > 10 kpa
        WJ - Intermediate bulk container, metal, pressure 10 kpa
        WK - Intermediate bulk container, steel, liquid
        WL - Intermediate bulk container, aluminium, liquid
        WM - Intermediate bulk container, metal, liquid
        WN - Intermediate bulk container, woven plastic, without coat/liner
        WP - Intermediate bulk container, woven plastic, coated
        WQ - Intermediate bulk container, woven plastic, with liner
        WR - Intermediate bulk container, woven plastic, coated and liner
        WS - Intermediate bulk container, plastic film
        WT - Intermediate bulk container, textile without coat/liner
        WU - Intermediate bulk container, natural wood, with inner liner
        WV - Intermediate bulk container, textile, coated
        WW - Intermediate bulk container, textile, with liner
        WX - Intermediate bulk container, textile, coated and liner
        WY - Intermediate bulk container, plywood, with inner liner
        WZ - Intermediate bulk container, reconstituted wood, with inner liner
        XA - Bag, woven plastic, without inner coat/liner
        XB - Bag, woven plastic, sift proof
        XC - Bag, woven plastic, water resistant
        XD - Bag, plastic film
        XF - Bag, textile, without inner coat/liner
        XG - Bag, textile, sift proof
        XH - Bag, textile, water resistant
        XJ - Bag, paper, multi-wall
        XK - Bag, paper, multi-wall, water resistant
        YA - Composite packaging, plastic receptacle in steel drum
        YB - Composite packaging, plastic receptacle in steel crate box
        YC - Composite packaging, plastic receptacle in aluminium drum
        YD - Composite packaging, plastic receptacle aluminium crate
        YF - Composite packaging, plastic receptacle in wooden box
        YG - Composite packaging, plastic receptacle in plywood drum
        YH - Composite packaging, plastic receptacle plywood box
        YJ - Composite packaging, plastic receptacle in fibre drum
        YK - Composite packaging, plastic receptacle fibreboard box
        YL - Composite packaging, plastic receptacle in plastic drum
        YM - Composite packaging, plastic receptacle in solid plastic box
        YN - Composite packaging, glass receptacle in steel drum
        YP - Composite packaging, glass receptacle in steel crate box
        YQ - Composite packaging, glass receptacle, in aluminium drum
        YR - Composite packaging, glass receptacle in aluminium crate
        YS - Composite packaging, glass receptacle in wooden box
        YT - Composite packaging, glass receptacle in plywood drum
        YV - Composite packaging, glass receptacle in wickerwork hamper
        YW - Composite packaging, glass receptacle in fibre drum
        YX - Composite packaging, glass receptacle in fibreboard box
        YY - Composite packaging, glass receptacle in expandable plastic pack
        YZ - Composite packaging, glass receptacle in solid plastic pack
        ZA - Intermediate bulk container, paper, multi-wall
        ZB - Bag, large
        ZC - Intermediate bulk container, paper, multi-wall, water resistant
        ZD - Intermediate bulk container, rigid plastic, with structural equipment, solids
        ZF - Intermediate bulk container, rigid plastic, freestanding, solids
        ZG - Intermediate bulk container, rigid plastic, with structural equipment, pressurised
        ZH - Intermediate bulk container, rigid plastic, freestanding, pressurised
        ZJ - Intermediate bulk container, rigid plastic, with structural equipment, liquids
        ZK - Intermediate bulk container, rigid plastic, freestanding, liquids
        ZL - Intermediate bulk container, composite, rigid plastic, solids
        ZM - Intermediate bulk container, composite, flexible plastic, solids
        ZN - Intermediate bulk container, composite, rigid plastic, pressurised
        ZP - Intermediate bulk container, composite, flexible plastic, pressurised
        ZQ - Intermediate bulk container, composite, rigid plastic, liquids
        ZR - Intermediate bulk container, composite, flexible plastic, liquids
        ZS - Intermediate bulk container, composite
        ZT - Intermediate bulk container, fibreboard
        ZU - Intermediate bulk container, flexible
        ZV - Intermediate bulk container, metal, other than steel
        ZW - Intermediate bulk container, natural wood
        ZX - Intermediate bulk container, plywood
        ZY - Intermediate bulk container, reconstituted wood
        ZZ - Mutually defined
        TXT;

        $uk_lines = explode("\n", trim($raw_uk));
        $en_lines = explode("\n", trim($raw_en));

        $result = [];

        foreach ($uk_lines as $i => $uk_line) {
            [$key, $uk_text] = explode(' - ', $uk_line, 2);
            [$key_en, $en_text] = explode(' - ', $en_lines[$i], 2);

            if ($key !== $key_en) {
                throw new \Exception("Keys don't match at line $i: $key vs $key_en");
            }

            PackageType::updateOrCreate(
                [
                    'key' => $key],
                [
                    'name' => [
                        'uk' => $uk_text,
                        'en' => $en_text,
                    ]
                ]
            );
        }
    }
}
