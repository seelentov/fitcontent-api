#!/bin/bash

functions=(
    'dcup() { sudo docker compose up "$1"; }'
    'dcupd() { sudo docker compose up "$1" -d; }'
    'dcdestroy() { sudo docker compose down "$1" --remove-orphans --volumes; }'
    'dcdown() { sudo docker compose down "$1"; }'
    'dclogs() { sudo docker compose logs "$1"; }'
    'dcwatch() { sudo docker compose logs "$1" --follow; }'
    'dcrestart() { sudo docker compose restart "$1"; }'
    'dcsh(){ sudo docker compose exec "$1" sh; }'
)

for func in "${functions[@]}"; do
    echo "$func" >>~/.bashrc || {
        echo "Ошибка при добавлении функции $func" >&2
        exit 1
    }
done

echo "Функции успешно добавлены. Не забудьте перезагрузить вашу оболочку (например, source ~/.bashrc)."
