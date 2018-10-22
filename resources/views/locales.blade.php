@php $locales = locales(); @endphp
@if($locales)
    <ul class="nav nav-tabs" id="localesTab" role="tablist">
        @foreach($locales as $locale)
            <li class="nav-item">
                <a class="nav-link active"
                   id="locale-{{$locale->code}}-tab"
                   data-toggle="tab"
                   href="#locale-{{$locale->code}}-content"
                   role="tab"
                   aria-controls="{{$locale->name}}"
                   aria-selected="{{ $loop->first ? 'true' : 'false' }}">{{ $locale->name }}</a>
            </li>
        @endforeach
    </ul>
@endif
