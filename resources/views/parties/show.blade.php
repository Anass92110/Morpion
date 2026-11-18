@extends('layouts.app')

@section('contenu')
    <p><a href="{{ route('parties.index') }}">Toutes les parties</a></p>
    <h1>Partie n°{{ $partie->id }}</h1>

    @if ($partie->estTerminee())
        @if ($partie->gagnant())
            <p><strong>{{ $partie->gagnant() }} a gagné !</strong></p>
        @else
            <p><strong>Match nul</strong></p>
        @endif
    @else
        <p>Au tour de : {{ $partie->joueur_courant }}</p>
    @endif

    @error('position')
        <p class="erreur">{{ $message }}</p>
    @enderror

    <form method="POST" action="{{ route('parties.jouer', $partie) }}">
        @csrf
        <div class="plateau">
            @foreach ($cases as $i => $case)
                @if ($case === '-' && ! $partie->estTerminee())
                    <button type="submit" class="case" name="position" value="{{ $i }}"></button>
                @else
                    <div class="case">{{ $case === '-' ? '' : $case }}</div>
                @endif
            @endforeach
        </div>
    </form>

    @if ($partie->estTerminee())
        <form method="POST" action="{{ route('parties.store') }}">
            @csrf
            <button type="submit">Nouvelle partie</button>
        </form>
    @endif

    @if ($coups->isNotEmpty())
        <form method="POST" action="{{ route('parties.annuler', $partie) }}">
            @csrf
            @method('DELETE')
            <button type="submit">Annuler le dernier coup</button>
        </form>
    @endif

    <h2>Historique</h2>
    <ol>
        @foreach ($coups as $coup)
            <li>{{ $coup->symbole }} en case {{ $coup->position }}</li>
        @endforeach
    </ol>
@endsection