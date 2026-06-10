# Design Document: Tier Level Card Enhancement

## Overview

The tier level card enhancement feature transforms the existing tier display component on the TurnCode dashboard into a visually engaging, gradient-based card that dynamically reflects the user's current tier and level. This enhancement aims to increase user motivation and provide immediate visual feedback about progression through the learning system.

### Goals

- Create a visually distinctive tier card with tier-specific gradient backgrounds
- Display user level and tier information prominently with clear typography
- Maintain responsive design across all device sizes
- Integrate seamlessly with the existing dashboard layout
- Support all 12 tier levels with unique color schemes

### Non-Goals

- Modifying the tier calculation logic in the User model
- Adding animations or transitions to tier changes
- Creating a separate tier history or progression tracking feature
- Implementing tier comparison with other users

## Architecture

### System Context

The tier card enhancement operates within the Laravel + Blade templating architecture of the TurnCode application. The feature is purely presentational and relies on existing backend data from the User model.

```
┌─────────────────────────────────────────────────────────────┐
│                     Dashboard View Layer                     │
│  ┌───────────────────────────────────────────────────────┐  │
│  │              dashboard.blade.php                      │  │
│  │  ┌─────────────────────────────────────────────────┐ │  │
│  │  │         Tier Card Component                     │ │  │
│  │  │  - Tier Badge (LV. X TIER)                      │ │  │
│  │  │  - Tier Name Display                            │ │  │
│  │  │  - Gradient Background Overlay                  │ │  │
│  │  └─────────────────────────────────────────────────┘ │  │
│  └───────────────────────────────────────────────────────┘  │
└─────────────────────────────────────────────────────────────┘
                            │
                            │ Reads data from
                            ▼
┌─────────────────────────────────────────────────────────────┐
│                      User Model                              │
│  - level (computed attribute)                                │
│  - tier (computed attribute)                                 │
│  - exp (database field)                                      │
└─────────────────────────────────────────────────────────────┘
```

### Component Architecture

The tier card consists of three main visual layers:

1. **Base Layer**: Background image (feather_gold.png) providing texture
2. **Gradient Overlay**: Dynamic tier-specific gradient applied via inline styles
3. **Content Layer**: Badge and tier name text elements

### Data Flow

```
User Model (exp field)
    │
    ├─> getLevelAttribute() → level number
    │
    └─> getTierAttribute() → tier name
            │
            └─> Dashboard Controller passes to view
                    │
                    └─> Blade template renders tier card
                            │
                            ├─> PHP determines RGB color from tier
                            │
                            └─> Inline style applies gradient background
```

## Components and Interfaces

### 1. Tier Card Blade Component

**Location**: `resources/views/dashboard.blade.php` (inline within stats-col section)

**Responsibilities**:
- Render tier badge with level number
- Display tier name with appropriate styling
- Apply tier-specific gradient background
- Maintain responsive layout

**Template Structure**:
```blade
<div class="stat-card tier-card">
    <div class="tier-overlay" style="background: linear-gradient(...)">
        <!-- Tier Badge -->
        <div style="display: flex; gap: 8px; align-items: center;">
            <span class="tier-badge">LV. {{ level }}</span>
            <span class="tier-label">TIER</span>
        </div>
        
        <!-- Tier Name -->
        <div class="tier-name">{{ tierName }}</div>
    </div>
</div>
```

**Props/Data**:
- `auth()->user()->level`: Integer representing user level
- `auth()->user()->tier`: String representing tier name
- `$rgbColor`: Computed RGB color string based on tier

### 2. Tier Color Mapping System

**Location**: `resources/views/dashboard.blade.php` (PHP section)

**Implementation**:
```php
$userTier = auth()->user()->tier ?? 'Initiate';
$tierColors = [
    'Initiate' => '168, 162, 158',
    'Explorer' => '34, 197, 94',
    'Operator' => '59, 130, 246',
    'Technician' => '139, 92, 246',
    'Specialist' => '236, 72, 153',
    'Professional' => '239, 68, 68',
    'Senior Professional' => '249, 115, 22',
    'Lead Engineer' => '234, 179, 8',
    'Architect' => '6, 182, 212',
    'Principal' => '15, 118, 110',
    'Strategist' => '225, 29, 72',
    'Visionary' => '218, 165, 32',
];
$rgbColor = $tierColors[$userTier] ?? '168, 162, 158';
```

**Interface**:
- Input: Tier name (string)
- Output: RGB color values (string format: "r, g, b")
- Default: Initiate color (168, 162, 158) for unrecognized tiers

### 3. CSS Styling System

**Location**: `public/css/dashboard.css`

**Key Classes**:

```css
.tier-card {
    background-image: url('/images/feather_gold.png');
    background-size: cover;
    background-position: left center;
    position: relative;
    border-radius: 160px;
    border: none;
    overflow: hidden;
    flex: 1;
}

.tier-overlay {
    position: absolute;
    inset: 0;
    padding: 1.5rem 2.5rem;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: flex-end;
}
```

**Inline Styles** (dynamic):
- Gradient background on `.tier-overlay`
- Badge styling with semi-transparent background
- Text shadows for depth

## Data Models

### User Model Attributes

The feature relies on existing computed attributes in the User model:

**level** (computed attribute):
```php
public function getLevelAttribute()
{
    $exp = $this->exp ?? 0;
    return min(200, floor(sqrt($exp / 100)) + 1);
}
```
- Type: Integer
- Range: 1-200
- Computed from: exp field
- Formula: floor(sqrt(exp / 100)) + 1

**tier** (computed attribute):
```php
public function getTierAttribute()
{
    $level = $this->level;
    
    if ($level >= 196) return 'Visionary';
    if ($level >= 171) return 'Strategist';
    if ($level >= 141) return 'Principal';
    if ($level >= 111) return 'Architect';
    if ($level >= 81) return 'Lead Engineer';
    if ($level >= 56) return 'Senior Professional';
    if ($level >= 36) return 'Professional';
    if ($level >= 21) return 'Specialist';
    if ($level >= 11) return 'Technician';
    if ($level >= 6) return 'Operator';
    if ($level >= 2) return 'Explorer';
    return 'Initiate';
}
```
- Type: String
- Values: One of 12 tier names
- Computed from: level attribute

### Tier Color Mapping

| Tier Name | Level Range | RGB Color | Visual Theme |
|-----------|-------------|-----------|--------------|
| Initiate | 1 | 168, 162, 158 | Gray/Neutral |
| Explorer | 2-5 | 34, 197, 94 | Green |
| Operator | 6-10 | 59, 130, 246 | Blue |
| Technician | 11-20 | 139, 92, 246 | Purple |
| Specialist | 21-35 | 236, 72, 153 | Pink |
| Professional | 36-55 | 239, 68, 68 | Red |
| Senior Professional | 56-80 | 249, 115, 22 | Orange |
| Lead Engineer | 81-110 | 234, 179, 8 | Yellow |
| Architect | 111-140 | 6, 182, 212 | Cyan |
| Principal | 141-170 | 15, 118, 110 | Teal |
| Strategist | 171-195 | 225, 29, 72 | Rose |
| Visionary | 196-200 | 218, 165, 32 | Gold |

## Correctness Properties

**Note on Property-Based Testing Applicability:**

This feature is **not suitable for property-based testing** because it is primarily a UI rendering feature. Property-based testing is designed for testing pure functions and logic with meaningful input variation, but this feature involves:

- Visual presentation (gradients, colors, text styling)
- Simple dictionary lookup (tier name → RGB color) with only 12 fixed mappings
- HTML/CSS rendering that requires visual verification
- No universal properties that can be expressed as "for all inputs X, property P(X) holds"

According to the design workflow guidelines, UI rendering features should use **visual regression testing, snapshot tests, and example-based unit tests** instead of property-based testing.

### Alternative Testing Approach

Since property-based testing doesn't apply, the following testing strategies are appropriate:

**1. Example-Based Unit Tests:**
- Verify each of the 12 tier-to-color mappings returns the correct RGB value
- Test default fallback behavior for unrecognized tiers
- Test null tier defaults to "Initiate"

**2. Visual Regression Tests:**
- Capture snapshots of tier card appearance for each tier
- Verify gradient rendering consistency
- Validate text readability and contrast

**3. Manual Visual Verification:**
- Human inspection of gradient smoothness
- Verification of responsive behavior across screen sizes
- Confirmation of integration with dashboard layout

**4. Integration Tests:**
- Verify tier card renders within dashboard without layout breaks
- Confirm data binding from User model works correctly
- Test that tier changes reflect properly after page refresh

## Error Handling

### Missing or Invalid Data

**Scenario**: User tier attribute returns null or unrecognized value

**Handling**:
```php
$userTier = auth()->user()->tier ?? 'Initiate';
$rgbColor = $tierColors[$userTier] ?? '168, 162, 158';
```
- Default to 'Initiate' tier if tier is null
- Default to Initiate color (gray) if tier name not found in mapping

**Result**: Card always renders with fallback styling, preventing visual breaks

### Unauthenticated User

**Scenario**: User not logged in when dashboard is accessed

**Handling**: Laravel's authentication middleware prevents access to dashboard
- Route protected by `auth` middleware
- User redirected to login page
- Tier card never renders for unauthenticated users

**No additional error handling needed** in the component itself

### Missing Background Image

**Scenario**: feather_gold.png image file not found

**Handling**:
- CSS background-image fails gracefully
- Gradient overlay still renders
- Card remains functional with gradient-only background

**Impact**: Reduced visual texture but no functional degradation

### CSS Class Conflicts

**Scenario**: Existing CSS classes conflict with new styling

**Mitigation**:
- Use existing `.tier-card` and `.tier-overlay` classes
- Apply inline styles for dynamic gradient (highest specificity)
- Maintain existing class structure to preserve layout

## Testing Strategy

### Manual Testing Approach

Since this is a UI rendering feature with no complex logic or user interactions beyond viewing, the testing strategy focuses on visual verification and responsive design validation.

#### 1. Visual Regression Testing

**Test Cases**:
- Verify tier card renders correctly for each of the 12 tiers
- Confirm gradient backgrounds display proper color transitions
- Validate badge and tier name text are readable against all gradient backgrounds
- Check text shadows provide adequate depth and contrast

**Test Method**:
- Create test users with exp values corresponding to each tier
- Log in as each test user and capture screenshots
- Compare against design specifications

#### 2. Responsive Design Testing

**Test Cases**:
- Desktop view (≥768px): Verify card maintains proper proportions
- Mobile view (<768px): Confirm text remains readable and layout doesn't break
- Tablet view (768px-1024px): Check intermediate sizing

**Test Method**:
- Use browser developer tools to test various viewport sizes
- Test on actual devices (mobile, tablet, desktop)
- Verify no text overflow or layout collapse

#### 3. Integration Testing

**Test Cases**:
- Tier card integrates properly within stats-col section
- Card aligns with other stat cards in header
- No layout disruption to surrounding dashboard components
- Gradient overlay doesn't obscure background image completely

**Test Method**:
- Visual inspection of dashboard layout
- Check spacing and alignment with adjacent elements
- Verify z-index layering is correct

#### 4. Data Binding Testing

**Test Cases**:
- Level number displays correctly from User model
- Tier name displays correctly from User model
- Gradient color matches tier name
- Default fallback works when tier is null or unrecognized

**Test Method**:
- Test with users at various exp levels
- Manually set tier to null in database and verify fallback
- Test with invalid tier name and verify default color applies

#### 5. Cross-Browser Testing

**Test Cases**:
- Chrome/Edge (Chromium)
- Firefox
- Safari (if available)

**Test Method**:
- Visual verification in each browser
- Check gradient rendering consistency
- Verify font rendering and text shadows

### Testing Checklist

**Before Deployment**:
- [ ] All 12 tier colors render correctly
- [ ] Badge displays "LV. X TIER" format properly
- [ ] Tier name uses correct font size (2rem) and weight (800)
- [ ] Gradient transitions smoothly from 30% to 90% to 100% opacity
- [ ] Text remains readable on all gradient backgrounds
- [ ] Card is responsive on mobile, tablet, and desktop
- [ ] No console errors in browser
- [ ] Background image loads correctly
- [ ] Default fallback works for invalid tier
- [ ] Layout integrates seamlessly with dashboard

### Known Limitations

1. **No Automated Tests**: Due to the purely visual nature of this feature, automated testing would require visual regression tools (e.g., Percy, Chromatic) which are not currently in the project stack.

2. **Real-Time Updates**: The tier card does not update in real-time when exp changes. Users must refresh the page to see tier changes. This is acceptable as tier changes are infrequent events.

3. **Accessibility**: While text contrast is maintained, no automated accessibility testing is performed. Manual verification with screen readers is recommended but not required for this enhancement.

### Future Testing Enhancements

If the project adopts visual regression testing tools:
- Implement snapshot tests for each tier's visual appearance
- Automate responsive design testing across viewport sizes
- Add accessibility audits with tools like axe-core
