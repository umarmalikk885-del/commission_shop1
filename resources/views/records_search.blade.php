<!DOCTYPE html>
<html lang="ur" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>{{ __('ریکارڈ تلاش') }} | کمیشن شاپ</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @include('components.admin-layout-styles')
    @include('components.sidebar-styles')
    @include('components.global-dark-mode-styles')
    @include('components.main-content-spacing')
</head>
<body class="rtl">
    <button class="mobile-menu-btn" onclick="toggleSidebar()"><i class="fa fa-bars"></i></button>
    @include('components.sidebar')

    <div class="main">
        <h1 style="font-family: 'Noto Nastaliq Urdu', serif;">{{ __('ریکارڈ تلاش') }}</h1>

        <div style="margin-bottom:15px; padding:10px; border:1px solid #cbd5e1; border-radius:8px; background:#f8fafc;">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:8px;">
                <div style="font-weight:600;">{{ __('نام یا بکری نمبر سے تلاش کریں') }}</div>
                <div style="font-size:0.8rem; color:#64748b;">{{ __('جزوی الفاظ اور بڑے چھوٹے حروف میں فرق نہیں') }}</div>
            </div>
            <input type="text" id="globalRecordSearch" class="legacy-input" placeholder="{{ __('نام، کوڈ، یا بکری نمبر لکھیں...') }}">
            <div id="globalRecordSearchResults" style="margin-top:10px;"></div>
        </div>

        @if(session('error'))
            <div style="padding:10px; border:2px solid #d00; background:#fee; color:#900; margin-bottom:10px;">
                {{ session('error') }}
            </div>
        @endif

        <form method="GET" action="{{ route('records.search') }}" style="display:grid; grid-template-columns: repeat(5, 1fr); gap:10px; margin-bottom:15px;">
            <div>
                <label>{{ __('بیوپاری نام') }}</label>
                <input type="text" name="owner" value="{{ $filters['owner'] ?? '' }}" class="legacy-input">
            </div>
            <div>
                <label>{{ __('گاہک نام') }}</label>
                <input type="text" name="purchaser" value="{{ $filters['purchaser'] ?? '' }}" class="legacy-input">
            </div>
            <div>
                <label>{{ __('اشیاء کی تفصیل') }}</label>
                <input type="text" name="product" value="{{ $filters['product'] ?? '' }}" class="legacy-input">
            </div>
            <div>
                <label>{{ __('سے تاریخ') }}</label>
                <input type="date" name="from" value="{{ $filters['from'] ?? '' }}" class="legacy-input">
            </div>
            <div>
                <label>{{ __('تک تاریخ') }}</label>
                <input type="date" name="to" value="{{ $filters['to'] ?? '' }}" class="legacy-input">
            </div>
            <div>
                <label>{{ __('فی صفحہ') }}</label>
                <input type="number" name="per_page" min="10" max="100" value="{{ $filters['per_page'] ?? 25 }}" class="legacy-input">
            </div>
            <div style="grid-column: span 4; display:flex; gap:10px; align-items:center;">
                <button type="submit" class="btn-legacy">{{ __('تلاش کریں') }}</button>
                <a href="{{ route('records.search', array_merge($filters, ['export' => 'csv'])) }}" class="btn-legacy">{{ __('CSV') }}</a>
            </div>
        </form>

        <div class="legacy-table-section">
            <div class="legacy-table-header">{{ __('نتائج') }}</div>
            <div class="legacy-table-container" style="overflow-x:auto;">
                <table class="legacy-table dense-table" style="min-width:1200px;">
                    <thead>
                        <tr>
                            <th>{{ __('بیوپاری') }}</th>
                            <th>{{ __('ٹرک نمبر') }}</th>
                            <th>{{ __('بکری نمبر') }}</th>
                            <th>{{ __('تاریخ (مالک)') }}</th>
                            <th>{{ __('گاہک') }}</th>
                            <th>{{ __('کوڈ گاہک') }}</th>
                            <th>{{ __('تاریخ (گاہک)') }}</th>
                            <th>{{ __('قسمِ مال') }}</th>
                            <th>{{ __('آئٹم کوڈ') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($results as $r)
                            <tr>
                                <td>{{ $r->trader }}</td>
                                <td>{{ $r->truck_number }}</td>
                                <td>{{ $r->goat_number }}</td>
                                <td>{{ $r->record_date }}</td>
                                <td>{{ $r->purchaser_name }}</td>
                                <td>{{ $r->book_code }}</td>
                                <td>{{ $r->transaction_date }}</td>
                                <td>{{ $r->item_type }}</td>
                                <td>{{ $r->item_code }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" style="text-align:center; padding:10px;">{{ __('کوئی ریکارڈ نہیں ملا') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div style="margin-top:10px; display:flex; gap:10px; align-items:center;">
            @if($results->currentPage() > 1)
                <a class="btn-legacy" href="{{ $results->previousPageUrl() }}">{{ __('پچھلا') }}</a>
            @endif
            @if($results->hasMorePages())
                <a class="btn-legacy" href="{{ $results->nextPageUrl() }}">{{ __('اگلا') }}</a>
            @endif
            <span>{{ __('صفحہ') }} {{ $results->currentPage() }} {{ __('از') }} {{ $results->lastPage() }}</span>
        </div>
    </div>

    <script>
        function toggleSidebar() {
            document.querySelector('.sidebar').classList.toggle('active');
            document.querySelector('.main').classList.toggle('active');
        }

        (function () {
            const input = document.getElementById('globalRecordSearch');
            const resultsContainer = document.getElementById('globalRecordSearchResults');
            if (!input || !resultsContainer) return;

            const endpoint = "{{ route('records.live-search') }}";
            let timer = null;

            function createRoleBadge(role) {
                const span = document.createElement('span');
                span.style.display = 'inline-block';
                span.style.padding = '2px 8px';
                span.style.borderRadius = '999px';
                span.style.fontSize = '0.75rem';
                span.style.marginInlineStart = '8px';
                span.style.fontWeight = '600';
                if (role === 'owner') {
                    span.style.backgroundColor = '#dbeafe';
                    span.style.color = '#1d4ed8';
                    span.textContent = "{{ __('بیوپاری') }}";
                } else if (role === 'purchaser') {
                    span.style.backgroundColor = '#dcfce7';
                    span.style.color = '#16a34a';
                    span.textContent = "{{ __('گاہک') }}";
                } else {
                    span.style.backgroundColor = '#ede9fe';
                    span.style.color = '#6d28d9';
                    span.textContent = "{{ __('ڈوئل (بیوپاری + گاہک)') }}";
                }
                return span;
            }

            function clearResults(message) {
                resultsContainer.innerHTML = '';
                if (message) {
                    const div = document.createElement('div');
                    div.style.padding = '8px 10px';
                    div.style.borderRadius = '6px';
                    div.style.backgroundColor = '#f1f5f9';
                    div.style.color = '#475569';
                    div.textContent = message;
                    resultsContainer.appendChild(div);
                }
            }

            function renderResults(data) {
                const people = data.people || [];
                if (!people.length) {
                    clearResults("{{ __('کوئی ریکارڈ نہیں ملا۔ کم حروف لکھیں یا صرف نام آزمائیں۔') }}");
                    return;
                }

                resultsContainer.innerHTML = '';

                people.forEach(function (person) {
                    const card = document.createElement('div');
                    card.style.border = '1px solid #e2e8f0';
                    card.style.borderRadius = '8px';
                    card.style.padding = '8px 10px';
                    card.style.marginBottom = '8px';
                    card.style.backgroundColor = '#ffffff';

                    const header = document.createElement('div');
                    header.style.display = 'flex';
                    header.style.justifyContent = 'space-between';
                    header.style.alignItems = 'center';
                    header.style.marginBottom = '6px';

                    const nameEl = document.createElement('div');
                    nameEl.textContent = person.name || '';
                    nameEl.style.fontWeight = '700';
                    nameEl.style.fontSize = '0.95rem';
                    nameEl.className = 'urdu-text';

                    const roleBadge = createRoleBadge(person.role_label || 'owner');

                    const left = document.createElement('div');
                    left.appendChild(nameEl);
                    left.appendChild(roleBadge);

                    const contact = document.createElement('div');
                    contact.style.fontSize = '0.75rem';
                    contact.style.textAlign = 'left';
                    const c = person.contact || {};
                    const parts = [];
                    if (c.mobile) parts.push("{{ __('موبائل') }}: " + c.mobile);
                    if (c.contact_number && c.contact_number !== c.mobile) parts.push("{{ __('رابطہ') }}: " + c.contact_number);
                    if (c.location) parts.push("{{ __('مقام') }}: " + c.location);
                    if (c.address) parts.push(c.address);
                    contact.textContent = parts.join(' | ');

                    header.appendChild(left);
                    header.appendChild(contact);

                    const table = document.createElement('table');
                    table.style.width = '100%';
                    table.style.borderCollapse = 'collapse';
                    table.style.fontSize = '0.8rem';

                    const thead = document.createElement('thead');
                    const headRow = document.createElement('tr');
                    ['{{ __('بکری نمبر') }}', '{{ __('ٹرک نمبر') }}', '{{ __('تاریخ') }}', '{{ __('تاریخ (گاہک)') }}', '{{ __('کردار') }}'].forEach(function (title) {
                        const th = document.createElement('th');
                        th.textContent = title;
                        th.style.padding = '4px 6px';
                        th.style.borderBottom = '1px solid #e2e8f0';
                        th.style.textAlign = 'center';
                        headRow.appendChild(th);
                    });
                    thead.appendChild(headRow);

                    const tbody = document.createElement('tbody');
                    (person.records || []).forEach(function (rec) {
                        const row = document.createElement('tr');
                        const cols = [
                            rec.goat_number || '',
                            rec.truck_number || '',
                            rec.record_date || '',
                            rec.transaction_date || '',
                            rec.role === 'purchaser' ? "{{ __('گاہک') }}" : "{{ __('بیوپاری') }}",
                        ];
                        cols.forEach(function (val) {
                            const td = document.createElement('td');
                            td.textContent = val;
                            td.style.padding = '3px 6px';
                            td.style.textAlign = 'center';
                            row.appendChild(td);
                        });
                        tbody.appendChild(row);
                    });

                    table.appendChild(thead);
                    table.appendChild(tbody);

                    card.appendChild(header);
                    card.appendChild(table);
                    resultsContainer.appendChild(card);
                });
            }

            function fetchResults(term) {
                const trimmed = term.trim();
                if (trimmed.length === 0) {
                    clearResults('');
                    return;
                }

                clearResults("{{ __('تلاش ہو رہی ہے...') }}");

                const url = endpoint + '?q=' + encodeURIComponent(trimmed);
                fetch(url, {
                    headers: {
                        'Accept': 'application/json'
                    }
                })
                    .then(function (resp) { return resp.json(); })
                    .then(function (data) { renderResults(data); })
                    .catch(function () {
                        clearResults("{{ __('تلاش میں مسئلہ آیا، دوبارہ کوشش کریں۔') }}");
                    });
            }

            input.addEventListener('input', function () {
                if (timer) {
                    clearTimeout(timer);
                }
                const value = this.value;
                timer = setTimeout(function () {
                    fetchResults(value);
                }, 400);
            });
        })();
    </script>
</body>
</html>
