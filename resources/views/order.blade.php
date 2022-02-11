@extends('layout')

@section('main')
<h1 class="heading">Создание заказа</h1>
<form class="form" method="POST">
    @if ($errors->any())
        <p class="status-message error">Заказ не был сформирован. Пожалуйста заполните все поля формы.</p>
    @endif

    @if ($success)
        <p class="status-message success">Ваш заказ был успешно сформирован! Спасибо!</p>
    @elseif (!empty($reason))
        <p class="status-message error">Заказ не был сформирован. Причина: {{ $reason }}</p>
    @endif

    @csrf
    <fieldset class="fieldset">
        <label for="name">ФИО</label>
        <p><input class="input-field" id="name" name="name" type="text" value="{{ old('name') }}" placeholder="Иванов Иван Иванович" autofocus></p>

        <label for="comment">Ваш комментарий</label>
        <p><textarea class="comment" id="comment" name="comment" cols="30" rows="10">{{ old('comment') }}</textarea></p>

        <label for="article">Артикул товара</label>
        <p><input class="input-field" id="article" name="article" type="text" value="{{ old('article') }}" placeholder="AZ105W"></p>

        <label for="manufacturer">Бренд товара</label>
        <p><input class="input-field" id="manufacturer" name="manufacturer" type="text" value="{{ old('manufacturer') }}" placeholder="Azalita"></p>
    </fieldset>
    <p class="form-submit-area">
        <button class="button-submit" type="submit">Создать!</button>
    </p>
</form>
@endsection
