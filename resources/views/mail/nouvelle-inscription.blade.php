<x-mail::message>
# Demande de compte OpenNAT

- Nom : {{ $user->name }}
- Adresse : {{ $user->email }}
- Organisation : {{ $user->organisation }}
- Fonction : {{ $user->fonction ?: 'non renseignée' }}
- Téléphone : {{ $user->phone ?: 'non renseigné' }}
- Demande du : {{ $user->created_at?->timezone('Europe/Paris')->format('d/m/Y H:i') }}

Le compte reste fermé tant qu'il n'est pas validé dans l'administration.

<x-mail::button :url="url('/admin/users')">
Ouvrir l'administration
</x-mail::button>
</x-mail::message>
