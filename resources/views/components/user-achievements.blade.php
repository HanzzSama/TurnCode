@props(['user', 'maxVisible' => 3])

@if(!empty($user->achievements) && is_array($user->achievements))
    <div class="user-achievements">
        @foreach(array_slice($user->achievements, 0, $maxVisible) as $achievement)
            <div class="achievement-badge" 
                 style="--badge-color: {{ $achievement['color'] ?? '#6b7280' }}"
                 title="{{ $achievement['title'] ?? 'Achievement' }}">
                <span class="achievement-icon">{{ $achievement['icon'] ?? '⭐' }}</span>
                <span class="achievement-title">{{ $achievement['title'] ?? '' }}</span>
            </div>
        @endforeach
        
        @if(count($user->achievements) > $maxVisible)
            <div class="achievement-badge more-badge" title="+{{ count($user->achievements) - $maxVisible }} more">
                <span class="achievement-title">+{{ count($user->achievements) - $maxVisible }}</span>
            </div>
        @endif
    </div>
@endif
