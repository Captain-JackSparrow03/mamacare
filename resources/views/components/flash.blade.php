@if(session('flash'))
@php $flash = session('flash'); @endphp
<div
  id="flash-msg"
  style="position:fixed;top:20px;right:20px;z-index:9999;transition:opacity .4s,transform .4s"
>
    <div class="flash-inner flash-{{ $flash['type'] }}">
        <span>{{ $flash['message'] }}</span>
        <button onclick="dismissFlash()" aria-label="Fermer">✕</button>
    </div>
</div>
<style>
.flash-inner{display:flex;align-items:center;gap:12px;padding:12px 18px;border-radius:12px;font-family:'DM Sans',sans-serif;font-size:14px;box-shadow:0 4px 24px rgba(0,0,0,.12)}
.flash-success{background:#EEF4F1;color:#2D6A50;border:1px solid #BCD8CC}
.flash-error{background:#FDF0F2;color:#9B2335;border:1px solid #F5C5CE}
.flash-warning{background:#FEF9EC;color:#92600A;border:1px solid #F5DFA0}
.flash-info{background:#EEF2FD;color:#2A4A9B;border:1px solid #C0CEEE}
.flash-inner button{background:none;border:none;cursor:pointer;opacity:.5;font-size:14px;margin-left:4px}
</style>
<script>
(function(){
  var el = document.getElementById('flash-msg');
  if(!el) return;
  function dismissFlash(){
    el.style.opacity='0';
    el.style.transform='translateY(-8px)';
    setTimeout(function(){ el.remove(); }, 400);
  }
  window.dismissFlash = dismissFlash;
  setTimeout(dismissFlash, 4500);
})();
</script>
@endif