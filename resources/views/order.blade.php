@extends('layout')

@section('main')
<h1>Создание заказа</h1>
<form action="" method="POST">
    <fieldset>
        <p>
            <label for="name">ФИО</label>
            <input id="name" name="name" type="text" placeholder="Иванов Иван Иванович">
        </p>
        <p>
            <label for="comment">Ваш комментарий</label>
            <div>
                <textarea id="comment" name="comment" cols="30" rows="10"></textarea>
            </div>
        </p>
        <p>
            <label for="vendor_code">Артикул товара</label>
            <input id="vendor_code" name="vendor_code" type="text" placeholder="ESK9909-1">
        </p>
        <p>
            <label for="brand">Бренд товара</label>
            <input id="brand" name="item_brand" type="text" placeholder="EasySky">
        </p>
    </fieldset>
    <p>
        <button type="submit">Создать!</button>
    </p>
</form>
@endsection
