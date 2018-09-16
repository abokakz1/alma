@extends('index.layout')

@section('content')
    <div class="page-header">
        <div class="back-link-block">
            <a href="{{ LaravelLocalization::getLocalizedURL(App::getLocale(), "/") }}"><i class="icon icon-arrow-left-g"></i><span><?php echo trans("messages.Назад <br>  на главную страницу"); ?></span></a>
        </div>
        <div class="page-title-block">
            <h1 class="page-title">{{trans("messages.Рекламодателям")}}</h1>
        </div>
    </div>
    <div class="content-block">
        @if(App::getLocale() == "kz")
            <p>
                «Алматы» телеарнасы Алматы және Алматы облысында, сондай-ақ Астана,
                Қарағанды, Ақтау, Атырау, Талдықорған қаласындағы кабельдік
                телеарнаның пакеттерінде (<b>«Алма ТВ»; «Диджитал ТВ» «Айкон», «ID
                TV»</b>) және Қазақстанның барлық территориясында <b>«Отау ТВ»</b> ұлттық
                жерсеріктік телехабар тарату жүйесі арқылы (Қазтелерадио) көрсетіледі.
            </p>
            <p>
                Сұрақтар бойынша мына телефонға хабарласыңыздар: 275-22-22 немесе
                <a href="mailto:reklama@almaty.tv">reklama@almaty.tv</a>
            </p>
        @else
            <p>Телеканал «Алматы» транслируется в Алматы и Алматинcкой области,
                а также во всех пакетах кабельного телевидения (<b>«Алма ТВ»;</b>
                <b>«Диджитал ТВ»</b> <b>«Айкон»</b>, <b>«ID TV»</b>) в городах
                Астана, Караганда, Актау, Атырау, Талдыкорган и на всей территории
                Казахстана через национальное спутниковое телевидение РК <b>«Отау
                    ТВ»</b> (Казтелерадио).</p>
            <p>По вопросам размещения обращаться по тел.: 275-22-22 или
                <a href="mailto:reklama@almaty.tv">reklama@almaty.tv</a></p>
        @endif
        <p><br></p>
        <p><br></p>
        <p>
            <a class="btn btn-primary mr-30 mb-10" 
                @if($pricelist != null)
                    href="/response_files/{{$pricelist->file}}"
                download="{{$pricelist->name}}">
                @endif
                <i class="icon icon-download-white"></i>
                {{trans("messages.Скачать прайс")}}
            </a>
            <a class="btn btn-primary mb-10" 
                @if($presentation != null)
                        href="/response_files/{{$presentation->file}}"
                
                download="{{$presentation->name}}"
                @endif
            >
            <i class="icon icon-download-white"></i>
            {{trans("messages.Скачать презентацию")}}
            </a>
        </p>
    </div>
@endsection