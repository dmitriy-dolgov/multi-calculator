**Общие ресурсы.**

Хранятся в папке `/common/share`
Можно использовать эту папку напрямую или через ссылки.

- /web/dist
- /web/external
- /web/js
- /web/img

- /web/sound - TODO: надо сделать
- /web/video - TODO: надо сделать
<br>
<br>

_Установка необходимых симлинков_

Делается из корня проекта, используются относительные линки.

в этих директориях
./frontend/web
./backend/web
 
назначить ссылки

ln -s ../../common/share/web/js js
ln -s ../../common/share/web/img img
ln -s ../../common/share/web/external external
ln -s ../../common/share/web/dist dist



