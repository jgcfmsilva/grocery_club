@extends('layouts.app')

@section('title', 'Email Validated')

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
@endpush

@section('content')
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card-body p-5">
                    <!-- Mensagem que será exibida -->
                    <div class="alert alert-success text-center" role="alert">
                        <h4 class="alert-heading">Email Validado com Sucesso!</h4>
                        <p>
                            O seu endereço de e-mail foi validado com sucesso! Já pode aproveitar todos os recursos da nossa plataforma.
                        </p>
                        <hr>
                        <p class="mb-0">
                            Se precisar de mais ajuda, entre em contato com o nosso suporte.
                        </p>
                    </div>

                    <!-- Extra: Link de navegação para a página inicial -->
                    <div class="text-center mt-4">
                        <a href="{{ route('home') }}" class="btn btn-primary rounded-2">
                            <i class="fa-solid fa-home mr-2"></i> Voltar à Página Inicial
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection