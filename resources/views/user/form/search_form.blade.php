@php
  $isOpen = request()->hasAny([
    'hall_id','date_from','date_to','model','weekday','day_type','seasonal','last_digit','columns'
  ]);

  // 表示項目の初期選択：DB側配列があればそれを優先（無ければ未選択）
  $columnsFromDb = $columnsFromDb ?? [];
  $selectedCols  = collect(request('columns', $columnsFromDb));

  // ホール初期値：未指定なら先頭
  $hallsList     = ($halls ?? collect());
  $defaultHallId = optional($hallsList->first())->hall_id;
  $currentHallId = request('hall_id', $defaultHallId);
@endphp

<div id="searchPanelWrap" class="bg-light border rounded-3 p-2 p-md-3 mb-3 small {{ $isOpen ? '' : 'is-collapsed' }}">
  {{-- ヘッダ（中央タイトル／右トグル） --}}
  <div class="position-relative mb-1">
    <h6 class="mb-0 text-primary fw-bold fs-6 text-center">検索条件</h6>
    <button id="searchFormToggleBtn"
            class="btn btn-sm btn-outline-secondary position-absolute top-0 end-0"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#searchFormBody"
            aria-expanded="{{ $isOpen ? 'true' : 'false' }}"
            aria-controls="searchFormBody">
      <i class="bi bi-sliders me-1"></i>
      <span class="align-middle toggle-label">{{ $isOpen ? '閉じる' : '開く' }}</span>
    </button>
  </div>

  {{-- 閉時は一段下げて重なり感を消す --}}
  <div class="collapsed-spacer {{ $isOpen ? 'd-none' : '' }}"></div>

  {{-- 本体（開閉） --}}
  <div id="searchFormBody" class="collapse {{ $isOpen ? 'show' : '' }}">
    <form method="GET" action="{{ route('user.home') }}">
      @csrf

      <div class="row g-2">
        {{-- ホール --}}
        <div class="col-12 col-md-4">
          <label class="form-label mb-1">ホール&nbsp;&#58;</label>
          <select name="hall_id" class="form-select form-select-sm" @disabled($hallsList->isEmpty())>
            @forelse($hallsList as $hall)
              <option value="{{ $hall->hall_id }}" @selected($currentHallId == $hall->hall_id)>
                {{ $hall->hall_name }}
              </option>
            @empty
            @endforelse
          </select>
          @if($hallsList->isEmpty())
            <div class="form-text text-danger">参照可能なホールがありません。</div>
          @endif
        </div>

        {{-- 期間 --}}
        <div class="col-6 col-md-4">
          <label class="form-label mb-1">スタート日付&nbsp;&#58;</label>
          <input type="date" name="date_from" class="form-control form-control-sm" value="{{ request('date_from') }}">
        </div>
        <div class="col-6 col-md-4">
          <label class="form-label mb-1">終了日付&nbsp;&#58;</label>
          <input type="date" name="date_to" class="form-control form-control-sm" value="{{ request('date_to') }}">
        </div>

        {{-- 機種絞り込み（1機種）＋サジェスト --}}
        <div class="col-12">
          <label class="form-label mb-1">機種絞り込み（1機種）&nbsp;&#58;</label>
          <div class="position-relative">
            <input type="text"
                   id="machine_display_name_input"
                   name="machine_display_name"
                   class="form-control form-control-sm"
                   placeholder="機種名を入力"
                   value="{{ request('machine_display_name') }}"
                   autocomplete="off"
                   aria-autocomplete="list"
                   aria-controls="machine_suggest"
                   aria-expanded="false">
            <input type="hidden" name="machine_id" id="machine_id_hidden" value="{{ request('machine_id') }}">
            <div id="machine_suggest"
                 class="list-group position-absolute w-100 shadow-sm"
                 style="z-index:1050; max-height: 240px; overflow:auto; display:none;"
                 role="listbox"></div>
          </div>
          <small class="form-text text-muted">文字を入力すると候補が表示されます。</small>
        </div>

        {{-- 曜日 --}}
        <div class="col-4 col-md-4">
          <label class="form-label mb-1">曜日&nbsp;&#58;</label>
          <select name="weekday" class="form-select form-select-sm">
            <option value="">指定なし</option>
            <option value="mon" @selected(request('weekday')==='mon')>月</option>
            <option value="tue" @selected(request('weekday')==='tue')>火</option>
            <option value="wed" @selected(request('weekday')==='wed')>水</option>
            <option value="thu" @selected(request('weekday')==='thu')>木</option>
            <option value="fri" @selected(request('weekday')==='fri')>金</option>
            <option value="sat" @selected(request('weekday')==='sat')>土</option>
            <option value="sun" @selected(request('weekday')==='sun')>日</option>
          </select>
        </div>

        {{-- 平日／休日／祝日／特日 --}}
        <div class="col-4 col-md-4">
          <label class="form-label mb-1">平日／休日／祝日／特日&nbsp;&#58;</label>
          <select name="day_type" class="form-select form-select-sm">
            <option value="">指定なし</option>
            <option value="weekday" @selected(request('day_type')==='weekday')>平日</option>
            <option value="weekend" @selected(request('day_type')==='weekend')>休日（土日）</option>
            <option value="holiday" @selected(request('day_type')==='holiday')>祝日</option>
            <option value="special" @selected(request('day_type')==='special')>特日</option>
          </select>
        </div>

        {{-- 期間イベント --}}
        <div class="col-12">
          <label class="form-label mb-1">期間イベント&nbsp;&#58;</label>
          <div class="d-flex flex-wrap gap-2">
            <label class="form-check me-3 mb-1">
              <input class="form-check-input me-1" type="checkbox" name="seasonal[]" value="newyear"
                     @checked(collect(request('seasonal'))->contains('newyear'))> 年末年始
            </label>
            <label class="form-check me-3 mb-1">
              <input class="form-check-input me-1" type="checkbox" name="seasonal[]" value="gw"
                     @checked(collect(request('seasonal'))->contains('gw'))> GW
            </label>
            <label class="form-check me-3 mb-1">
              <input class="form-check-input me-1" type="checkbox" name="seasonal[]" value="obon"
                     @checked(collect(request('seasonal'))->contains('obon'))> お盆
            </label>
            <label class="form-check me-3 mb-1">
              <input class="form-check-input me-1" type="checkbox" name="seasonal[]" value="sw"
                     @checked(collect(request('seasonal'))->contains('sw'))> SW
            </label>
          </div>
        </div>

        {{-- 下一桁 --}}
        <div class="col-12 col-md-3">
          <label class="form-label mb-1">下一桁&nbsp;&#58;</label>
          <select name="last_digit" class="form-select form-select-sm">
            <option value="">指定なし</option>
            @for($i=0; $i<=9; $i++)
              <option value="{{ $i }}" @selected(request('last_digit')===(string)$i)>{{ $i }}</option>
            @endfor
          </select>
        </div>
      </div>

      {{-- 表示項目（見出し） --}}
      <div class="text-center mt-2 mb-1">
        <h6 class="mb-0 text-primary fw-bold fs-6">表示項目</h6>
      </div>

      {{-- 表示項目（選択はDB/Requestのみ。無ければ未選択） --}}
      @php
        $allCols = [
          'machine_count'       => '台数',
          'sales'               => '売上',
          'gross_profit'        => '粗利',
          'sales_per_machine'   => '台売上',
          'profit_per_machine'  => '台粗利',
          'profit_margin'       => '利益率',
          'prize_amount'        => '景品額',
          'wari_su'             => '割数',
          'payout'              => '出玉率',
          'unit_price'          => '玉単価（コイン単価）',
          'win_rate'            => '勝率',
        ];
      @endphp

      <div class="row gx-2 gy-1">
        @foreach($allCols as $key => $label)
          <div class="col-6 col-md-2">
            <label class="form-check form-check-tight">
              <input class="form-check-input me-1" type="checkbox" name="columns[]"
                     id="col-{{ $key }}" value="{{ $key }}"
                     @checked($selectedCols->contains($key))>
              <span class="form-check-label">{{ $label }}</span>
            </label>
          </div>
        @endforeach
      </div>

      <div class="d-flex justify-content-center mt-2">
        {{-- 検索ボタンはログインの送信ボタンと同色に揃えるため .btn-switch を使用 --}}
        <button type="submit" class="btn btn-primary btn-sm fw-bold px-4">検索</button>
      </div>
    </form>
  </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
  // 開閉トグルの文言を動的に切り替え
  const btn   = document.getElementById('searchFormToggleBtn');
  const label = btn?.querySelector('.toggle-label');
  const body  = document.getElementById('searchFormBody');

  if (btn && label && body) {
    const updateText = () => {
      const shown = body.classList.contains('show');
      label.textContent = shown ? '閉じる' : '開く';
      btn.setAttribute('aria-expanded', shown ? 'true':'false');
    };
    body.addEventListener('shown.bs.collapse', updateText);
    body.addEventListener('hidden.bs.collapse', updateText);
  }
});
</script>
@endpush
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
  // {machine_id, machine_display_name} の形で渡す
  const machines = @json(
    ($machines ?? collect())
      ->map(function($m){
        return [
          'machine_id' => $m->machine_id,
          'machine_display_name' => $m->machine_display_name
        ];
      })
      ->values()
  );

  const input  = document.getElementById('machine_display_name_input');
  const hidden = document.getElementById('machine_id_hidden');
  const menu   = document.getElementById('machine_suggest');

  if (!input || !hidden || !menu) return;

  let activeIndex = -1;
  let currentItems = [];

  const norm = (s) => (s ?? '').toString().trim().toLowerCase();

  function matchMachines(query){
    const q = norm(query);
    if(!q) return [];
    return machines.filter(m => norm(m.machine_display_name).includes(q));
  }

  function render(items){
    menu.innerHTML = '';
    activeIndex = -1;
    currentItems = items.slice(0, 8);

    if(currentItems.length === 0){
      menu.style.display = 'none';
      return;
    }

    currentItems.forEach((m, idx)=>{
      const btn = document.createElement('button');
      btn.type = 'button';
      btn.className = 'list-group-item list-group-item-action';
      btn.textContent = m.machine_display_name; // ←ここも machine_display_name
      btn.addEventListener('mousedown', function(ev){
        ev.preventDefault();
        choose(idx);
      });
      menu.appendChild(btn);
    });
    menu.style.display = 'block';
  }

  function choose(idx){
    const m = currentItems[idx];
    if(!m) return;
    input.value  = m.machine_display_name; // ←入力欄に表示名
    hidden.value = m.machine_id;           // ←hiddenにID
    menu.style.display = 'none';
  }

  input.addEventListener('input', function(){
    hidden.value = '';
    const q = input.value;
    if(!q.trim()){ menu.style.display = 'none'; return; }
    render(matchMachines(q));
  });
});
</script>
@endpush
