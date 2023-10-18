<li class="nav-item login">
  <a class="nav-link" href="/livros"> <i class="bi bi-book"></i>&nbsp; Livros</a>
  @if (auth()?->user()?->is_admin)
  <a class="nav-link" href="/alunos"> <i class="bi bi-person"></i>&nbsp; Alunos</a>
  <a class="nav-link" href="/usuarios"> <i class="bi bi-person-gear"></i>&nbsp; Usuários</a>
  @endif
</li>