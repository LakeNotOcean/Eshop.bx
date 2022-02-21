# -*- coding: utf-8 -*-
import time
from selenium import webdriver
from selenium.webdriver.chrome.options import Options
import dbFuncs
import saveImage
from selenium.webdriver.common.action_chains import ActionChains
from PIL import Image

specsName = {
    'Гарантия': 1,
    'Страна-производитель': 2,
    'Объем видеопамяти': 3,
    'Тип памяти': 4,
    'Частота памяти': 5,
    'Тип': 6,
    'Год релиза': 7,
    'Длина видеокарты': 8,
    'Толщина видеокарты': 9,
    'Подсветка элементов видеокарты': 10,
    'Техпроцесс': 11,
    'Штатная частота работы видеочипа': 12,
    'Максимальная температура процессора (C)': 13,
    'Поддержка трассировки лучей': 16,
    'Видеоразъемы': 14,
    'Количество подключаемых одновременно мониторов': 15,
    'Интерфейс подключения': 17,
    'Версия PCI Express': 18,
}

specsExample = {'Гарантия': '36 мес.', 'Страна-производитель': 'Китай', 'Тип': 'видеокарта', 'Серия': 'GIGABYTE AORUS',
                'Модель': 'GIGABYTE AORUS GeForce RTX 3080 XTREME', 'Год релиза': '2020',
                'Код производителя': '[GV-N3080AORUS X-10GD]', 'Предназначена для майнинга (добыча криптовалют)': 'нет',
                'LHR': 'нет', 'Объем видеопамяти': '10 ГБ', 'Тип памяти': 'GDDR6X',
                'Пропускная способность памяти на один контакт': '19 Гбит/с', 'Разрядность шины памяти': '320 бит',
                'Максимальная пропускная способность памяти': '760 Гбайт/сек', 'Микроархитектура': 'Ampere',
                'Кодовое название графического процессора': 'GA102', 'Техпроцесс': '8 нм',
                'Штатная частота работы видеочипа': '1440 МГц', 'Турбочастота': '1905 МГц',
                'Количество универсальных процессоров (ALU)': '8704', 'Число текстурных блоков': '272',
                'Число блоков растеризации': '88', 'Максимальная температура процессора (C)': '93°',
                'Поддержка трассировки лучей': 'да', 'Аппаратное ускорение трассировки лучей (RT-ядра)': '68',
                'Тензорные ядра': '272', 'Пиковая производительность чипов в FP32': '29760 GFLOPS',
                'Видеоразъемы': 'DisplayPort x3, HDMI x3', 'Версия HDMI': '2.1', 'Версия DisplayPort': '1.4a',
                'Максимальное разрешение': '8K UHD (Ultra HD), 7680x4320',
                'Количество подключаемых одновременно мониторов': '4 шт', 'Интерфейс подключения': 'PCI-E',
                'Версия PCI Express': '4.0', 'Поддержка мультипроцессорной конфигурации': 'не поддерживается',
                'Необходимость дополнительного питания': 'есть', 'Разъемы дополнительного питания': '8-pin x3',
                'Максимальное энергопотребление': '370 Вт', 'Лимит энергопотребления': '450 Вт',
                'Рекомендуемый блок питания': '850 Вт', 'Название системы охлаждения': 'GIGABYTE MAX-COVERED',
                'Тип охлаждения': 'активное воздушное', 'Тип и количество установленных вентиляторов': '3 осевых',
                'Управление скоростью вращения': 'до полной остановки', 'Низкопрофильная карта (Low Profile)': 'нет',
                'Количество занимаемых слотов расширения': '3.5', 'Длина видеокарты': '319 мм',
                'Толщина видеокарты': '70 мм', 'Вес': '1860 г', 'Комплектация': 'документация, наклейка, фигурка AORUS',
                'Подсветка элементов видеокарты': 'есть', 'Синхронизация RGB подсветки': 'GIGABYTE RGB Fusion',
                'LCD дисплей': 'есть', 'Переключатель BIOS': 'есть'}


def writeLinks(browser, url):
    for i in range(1, 11):
        browser.get(url + '&p=' + str(i))
        items = browser.find_elements_by_css_selector('a.catalog-product__name')
        with open('itemsLinks.txt', 'a') as linksFile:
            for item in items:
                linksFile.write(item.get_attribute('href') + '\n')


def getPrice(browser) -> str:
    prices = browser.find_elements_by_css_selector('div.product-buy__price')
    for dirtyPrice in prices:
        price = dirtyPrice.text
        price = price.replace(' ', '')
        price = price.split('₽')[0]
        return price


def getTitle(browser) -> str:
    title = browser.find_elements_by_css_selector('div.product-card__breadcrumbs > ol > li:nth-child(6) > a > span')[
        0].text
    return title.split(' [')[0]


def getFullDescription(browser) -> str:
    description = browser.find_elements_by_css_selector('div.product-card-description-text > p')[0].text
    return description


def getShortDescription(browser) -> str:
    fullDesc = getFullDescription(browser)
    shortDesc = fullDesc.split('. ')[0] + '.'
    return shortDesc


def getItemSpecs(browser) -> dict:
    specsTitles = browser.find_elements_by_css_selector('div.product-characteristics__spec-title')
    specsValues = browser.find_elements_by_css_selector('div.product-characteristics__spec-value')
    specsAmount = len(specsTitles)
    itemSpecs = {}
    for i in range(specsAmount):
        itemSpecs[specsTitles[i].text] = specsValues[i].text
    return itemSpecs


def getPictures(browser, item_id):
    imageOthers = browser.find_elements_by_css_selector('div.tns-item')
    is_main = True
    for image in imageOthers:
        imageElements = image.find_elements_by_tag_name('picture')
        if not imageElements:
            continue

        hover = ActionChains(browser).move_to_element(image)
        hover.perform()
        time.sleep(.5)

        imageElement = browser.find_elements_by_css_selector('picture.product-images-slider__main > source')[0]
        imagePath = imageElement.get_attribute('srcset')

        imageName = saveImage.saveMainImage(imagePath, 'original/')
        original_id = recordOriginalPicture(item_id, imageName, is_main)

        resizeImage(imageName, original_id)

        is_main = False


def initBrowser():
    chrome_options = Options()
    WINDOW_SIZE = "1720,880"
    # chrome_options.add_argument("--headless")
    chrome_options.add_argument("--window-size=%s" % WINDOW_SIZE)
    browser = webdriver.Chrome(options=chrome_options)
    return browser


def get_products(browser, url, item_id=0):
    browser.get(url)
    time.sleep(2)
    # specs = getItemSpecs(browser)
    # price = getPrice(browser)
    # title = getTitle(browser)
    # shortDesc = getShortDescription(browser)
    # fullDesc = getFullDescription(browser)
    # recordItem(title, price, shortDesc, fullDesc)
    # recordSpecs(specs, item_id)
    getPictures(browser, item_id)


def recordItem(title, price, shortDesc, fullDesc):
    query = f"insert into up_item (TITLE, PRICE, SHORT_DESC, FULL_DESC, SORT_ORDER, ACTIVE, DATE_CREATE, DATE_UPDATE, ITEM_TYPE_ID) values ('{title}', {price}, '{shortDesc}', '{fullDesc}', 1, 1, now(), now(), 1)"
    dbFuncs.completeQuery(query)


def recordSpecs(specs, item_id):
    for key in specs.keys():
        if key in specsName.keys():
            query = f"insert into up_item_spec (ITEM_ID, SPEC_TYPE_ID, VALUE) VALUES ({item_id}, {specsName[key]}, '{specs[key]}')"
            dbFuncs.completeQuery(query)


def recordOriginalPicture(item_id, img_name, is_main):
    if is_main:
        is_main = 1
    else:
        is_main = 0
    fullPath = 'img/original/' + img_name + '.webp'
    query = f"insert into up_original_image (PATH, ITEM_ID, IS_MAIN) VALUES ('{fullPath}', {item_id}, {is_main})"
    dbFuncs.completeQuery(query)

    last_id_query = "select id from up_original_image order by id desc limit 1"
    original_image_id = dbFuncs.completeQuery(last_id_query)[0][0]
    return original_image_id


def recordResizedImage(orig_image_id, path, size):
    query = f"insert into up_image_with_size (ORIGINAL_IMAGE_ID, PATH, SIZE) values ({orig_image_id}, '{path}', '{size}')"
    dbFuncs.completeQuery(query)



def resizeImage(original_image_name, original_id):
    sizes = {'small': 130, 'medium': 400, 'big': 800}
    for word, size in sizes.items():
        fullPath = 'C:/Users/mrart/server/domains/eshop.bx/public/img/original/' + original_image_name
        img = Image.open(fullPath + '.webp')
        width, height = img.size
        asp_rat = width / height

        if width > height:
            new_width = size
            new_height = size/asp_rat
        else:
            new_height = size
            new_width = size / asp_rat

        if width < new_width or height < new_height:
            new_width = width
            new_height = height

        img = img.resize((int(new_width), int(new_height)), Image.ANTIALIAS)
        newPath = f'C:/Users/mrart/server/domains/eshop.bx/public/img/{word}/' + original_image_name
        for extension in ['.jpg', '.webp']:
            if extension == '.jpg':
                imgRGB = img.convert('RGB')
                imgRGB.save(newPath + extension)
                continue
            img.save(newPath + extension)

        original_image_path = f"img/{word}/{original_image_name}"

        recordResizedImage(original_id, original_image_path, word)


urlItem = 'https://www.dns-shop.ru/product/5a7b4c6d0bc3c823/videokarta-msi-geforce-210-n210-1gd3lp/characteristics/'
urlCatalog = 'https://www.dns-shop.ru/catalog/17a89aab16404e77/videokarty/?price=15000-284999'

if __name__ == '__main__':
    browser = initBrowser()
    try:
        with open('itemsLinks.txt') as linksFile:
            links = linksFile.read().splitlines()
        item_id = 28
        for link in links[27:]:
            get_products(browser, link, item_id)
            item_id += 1
    finally:
        browser.quit()
