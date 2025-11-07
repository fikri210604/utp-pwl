<style>
  .kop-wrap { border-bottom: 2px solid #000; padding-bottom: 8px; margin-bottom: 18px; }
  .kop-table { width: 100%; }
  .kop-table td { vertical-align: middle; }
  .kop-logo { width: 70px; height: 70px; object-fit: contain; }
  .kop-center { text-align: center; }
  .kop-title { font-weight: 700; font-size: 18px; margin: 0; }
  .kop-sub { margin: 0; font-size: 12px; }
  @media (max-width: 575.98px) {
    .kop-logo { width: 52px; height: 52px; }
    .kop-title { font-size: 16px; }
    .kop-sub { font-size: 11px; }
    .kop-wrap { padding-bottom: 6px; margin-bottom: 14px; }
  }
</style>
<div class="kop-wrap">
  <table class="kop-table">
    <tr>
      <td style="width: 15%; text-align:left;">
        <img class="kop-logo" src="{{ $kopLeft ?? asset(config('kop.left_logo')) }}" alt="Logo Kiri">
      </td>
      <td class="kop-center" style="width: 70%;">
        <p class="kop-title">{{ config('kop.org_name') }}</p>
        <p class="kop-sub">{{ config('kop.address') }}</p>
        <p class="kop-sub">{{ config('kop.contact') }}</p>
      </td>
      <td style="width: 15%; text-align:right;">
        <img class="kop-logo" src="{{ $kopRight ?? asset(config('kop.right_logo')) }}" alt="Logo Kanan">
      </td>
    </tr>
  </table>
</div>
