# Requirements Document

## Introduction

Sistem tier/level card enhancement adalah fitur untuk meningkatkan tampilan visual card tier pengguna pada dashboard aplikasi pembelajaran coding TurnCode. Fitur ini akan menampilkan level dan nama tier pengguna dengan desain yang lebih menarik menggunakan gradient background dan badge yang jelas, sehingga meningkatkan motivasi pengguna untuk terus belajar dan naik level.

## Glossary

- **Tier_Card**: Komponen UI yang menampilkan informasi tier dan level pengguna saat ini
- **User**: Pengguna aplikasi TurnCode yang memiliki akun dan sedang login
- **Level**: Angka numerik yang merepresentasikan tingkat kemajuan pengguna berdasarkan EXP (experience points)
- **Tier**: Nama kategori/peringkat pengguna berdasarkan level (contoh: Initiate, Explorer, Operator, dll)
- **EXP**: Experience points yang dikumpulkan pengguna melalui aktivitas pembelajaran
- **Dashboard**: Halaman utama aplikasi setelah pengguna login
- **Tier_Badge**: Elemen visual yang menampilkan "LV. X TIER" pada card
- **Gradient_Background**: Background dengan efek gradasi warna yang sesuai dengan tier pengguna
- **Tier_System**: Sistem yang mengelola 12 tier berbeda dari Initiate hingga Visionary

## Requirements

### Requirement 1: Display Current Tier Information

**User Story:** As a user, I want to see my current tier and level displayed prominently on the dashboard, so that I can quickly understand my progress in the learning system.

#### Acceptance Criteria

1. THE Tier_Card SHALL display the User's current level number
2. THE Tier_Card SHALL display the User's current tier name
3. WHEN a User views the Dashboard, THE Tier_Card SHALL retrieve level data from the User model's level attribute
4. WHEN a User views the Dashboard, THE Tier_Card SHALL retrieve tier data from the User model's tier attribute
5. THE Tier_Card SHALL display tier information within the header section of the Dashboard

### Requirement 2: Render Tier Badge Component

**User Story:** As a user, I want to see a clear badge showing my level and tier label, so that I can easily identify my current rank at a glance.

#### Acceptance Criteria

1. THE Tier_Badge SHALL display the text "LV. {level} TIER" where {level} is the User's current level number
2. THE Tier_Badge SHALL use white text color with font weight of 700
3. THE Tier_Badge SHALL use a font size of 0.75rem with letter spacing of 0.5px
4. THE Tier_Badge SHALL have a semi-transparent black background with opacity of 0.2
5. THE Tier_Badge SHALL have padding of 2px horizontal and 8px vertical
6. THE Tier_Badge SHALL have a border radius of 20px for rounded appearance
7. THE Tier_Badge SHALL be positioned at the top of the Tier_Card

### Requirement 3: Display Tier Name with Styling

**User Story:** As a user, I want to see my tier name displayed in large, bold text, so that it stands out and reinforces my achievement.

#### Acceptance Criteria

1. THE Tier_Card SHALL display the tier name below the Tier_Badge
2. THE Tier_Card SHALL render the tier name with font size of 2rem
3. THE Tier_Card SHALL render the tier name with font weight of 800
4. THE Tier_Card SHALL render the tier name with line height of 1.1
5. THE Tier_Card SHALL render the tier name in white color
6. THE Tier_Card SHALL apply a text shadow of "0 1px 2px rgba(0,0,0,0.2)" to the tier name for depth

### Requirement 4: Apply Tier-Specific Gradient Background

**User Story:** As a user, I want the tier card to have a unique gradient background color based on my tier, so that each tier feels distinct and visually rewarding.

#### Acceptance Criteria

1. THE Tier_Card SHALL apply a Gradient_Background based on the User's current tier
2. THE Gradient_Background SHALL use a linear gradient from left to right
3. THE Gradient_Background SHALL start with 30% opacity of the tier color at 0% position
4. THE Gradient_Background SHALL transition to 90% opacity of the tier color at 40% position
5. THE Gradient_Background SHALL end with 100% opacity of the tier color at 100% position
6. WHEN the User's tier is "Initiate", THE Tier_Card SHALL use RGB color (168, 162, 158)
7. WHEN the User's tier is "Explorer", THE Tier_Card SHALL use RGB color (34, 197, 94)
8. WHEN the User's tier is "Operator", THE Tier_Card SHALL use RGB color (59, 130, 246)
9. WHEN the User's tier is "Technician", THE Tier_Card SHALL use RGB color (139, 92, 246)
10. WHEN the User's tier is "Specialist", THE Tier_Card SHALL use RGB color (236, 72, 153)
11. WHEN the User's tier is "Professional", THE Tier_Card SHALL use RGB color (239, 68, 68)
12. WHEN the User's tier is "Senior Professional", THE Tier_Card SHALL use RGB color (249, 115, 22)
13. WHEN the User's tier is "Lead Engineer", THE Tier_Card SHALL use RGB color (234, 179, 8)
14. WHEN the User's tier is "Architect", THE Tier_Card SHALL use RGB color (6, 182, 212)
15. WHEN the User's tier is "Principal", THE Tier_Card SHALL use RGB color (15, 118, 110)
16. WHEN the User's tier is "Strategist", THE Tier_Card SHALL use RGB color (225, 29, 72)
17. WHEN the User's tier is "Visionary", THE Tier_Card SHALL use RGB color (218, 165, 32)
18. IF the User's tier is not recognized, THE Tier_Card SHALL default to RGB color (168, 162, 158)

### Requirement 5: Maintain Responsive Layout

**User Story:** As a user, I want the tier card to display properly on different screen sizes, so that I can view my tier information on any device.

#### Acceptance Criteria

1. THE Tier_Card SHALL maintain its visual hierarchy on desktop screens (width >= 768px)
2. THE Tier_Card SHALL maintain its visual hierarchy on mobile screens (width < 768px)
3. THE Tier_Card SHALL ensure text remains readable at all supported screen sizes
4. THE Tier_Card SHALL preserve the gradient background effect across all screen sizes

### Requirement 6: Integrate with Existing Dashboard Layout

**User Story:** As a user, I want the enhanced tier card to fit seamlessly into the existing dashboard design, so that the interface remains cohesive and professional.

#### Acceptance Criteria

1. THE Tier_Card SHALL be positioned in the stats-col section of the Dashboard header
2. THE Tier_Card SHALL maintain the existing stat-card and tier-card CSS classes
3. THE Tier_Card SHALL preserve the tier-overlay structure for gradient rendering
4. THE Tier_Card SHALL align with other stat cards in the header section
5. THE Tier_Card SHALL not disrupt the layout of surrounding dashboard components

### Requirement 7: Reflect Real-Time Tier Changes

**User Story:** As a user, I want the tier card to update automatically when I level up or change tiers, so that I always see my current status without refreshing the page.

#### Acceptance Criteria

1. WHEN the User's EXP increases and causes a level change, THE Tier_Card SHALL reflect the new level number
2. WHEN the User's level crosses a tier threshold, THE Tier_Card SHALL update to display the new tier name
3. WHEN the User's tier changes, THE Tier_Card SHALL update the Gradient_Background to match the new tier color
4. THE Tier_Card SHALL retrieve updated tier and level data from the User model attributes

### Requirement 8: Ensure Visual Consistency with Design System

**User Story:** As a user, I want the tier card to follow the application's design language, so that the interface feels polished and unified.

#### Acceptance Criteria

1. THE Tier_Card SHALL use the Inter font family consistent with the Dashboard
2. THE Tier_Card SHALL use border radius values consistent with other dashboard cards
3. THE Tier_Card SHALL use spacing and padding consistent with the dashboard design system
4. THE Tier_Card SHALL use color opacity values that maintain readability against the gradient background
5. THE Tier_Card SHALL apply shadows and visual effects consistent with other dashboard components
