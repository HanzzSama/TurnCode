# Requirements Document

## Introduction

This document specifies the requirements for improving the buddy container transition smoothness when switching between automatic chat mode (Chat Otomatis) and chat input mode (Chat Input). The current implementation uses CSS transitions with max-height, opacity, and transform properties, but the transitions can feel abrupt or janky due to container height changes and element visibility timing. This enhancement will provide a more polished, fluid user experience when toggling between the two chat modes.

## Glossary

- **Buddy_Container**: The main UI component that displays the buddy avatar, chat interface, and mode controls
- **Auto_Mode**: The automatic chat mode where the buddy displays typewriter text, prompt buttons, and pill tags automatically
- **Chat_Mode**: The manual chat input mode where users can type free-form messages to the buddy
- **Mode_Switch**: The toggle control that allows users to switch between Auto_Mode and Chat_Mode
- **Chat_Log**: The scrollable message history container visible only in Chat_Mode
- **Chat_Input_Row**: The text input field and send button visible only in Chat_Mode
- **Typing_Text**: The typewriter-style text display visible only in Auto_Mode
- **Prompt_Wrapper**: The Yes/No button container visible only in Auto_Mode
- **Pills_Row**: The quick action buttons (Tips Belajar, Status, etc.) visible only in Auto_Mode
- **Transition_Engine**: The CSS and JavaScript logic that orchestrates the mode switching animation

## Requirements

### Requirement 1: Smooth Container Height Transition

**User Story:** As a user, I want the buddy container to smoothly adjust its height when switching modes, so that the transition feels natural and not jarring.

#### Acceptance Criteria

1. WHEN the user switches from Auto_Mode to Chat_Mode, THE Buddy_Container SHALL animate its height change over a duration between 350ms and 500ms
2. WHEN the user switches from Chat_Mode to Auto_Mode, THE Buddy_Container SHALL animate its height change over a duration between 350ms and 500ms
3. THE Transition_Engine SHALL use an easing function that provides smooth acceleration and deceleration (such as cubic-bezier(0.16, 1, 0.3, 1) or ease-in-out)
4. THE Buddy_Container SHALL maintain its width throughout the transition
5. THE Buddy_Container SHALL not cause layout shifts in surrounding elements during the transition

### Requirement 2: Coordinated Element Visibility Timing

**User Story:** As a user, I want elements to appear and disappear in a coordinated manner during mode switching, so that the transition looks polished and professional.

#### Acceptance Criteria

1. WHEN switching to Chat_Mode, THE Typing_Text SHALL fade out and collapse before THE Chat_Log and Chat_Input_Row fade in and expand
2. WHEN switching to Auto_Mode, THE Chat_Log and Chat_Input_Row SHALL fade out and collapse before THE Typing_Text fades in and expands
3. THE Transition_Engine SHALL stagger element animations with a delay between 50ms and 150ms to create a sequential effect
4. THE Transition_Engine SHALL ensure opacity transitions complete within 300ms to 400ms
5. THE Transition_Engine SHALL ensure transform transitions (translateY) complete within 350ms to 450ms

### Requirement 3: Prevent Visual Glitches During Transition

**User Story:** As a user, I want the mode transition to be free of visual glitches, so that the interface feels high-quality and reliable.

#### Acceptance Criteria

1. WHEN elements are transitioning, THE Transition_Engine SHALL prevent content overflow by maintaining overflow: hidden on collapsing elements
2. WHEN elements are fading out, THE Transition_Engine SHALL set pointer-events: none to prevent interaction with disappearing elements
3. WHEN elements are fading in, THE Transition_Engine SHALL restore pointer-events: auto only after opacity reaches 0.8 or higher
4. THE Transition_Engine SHALL prevent text reflow or wrapping changes during height transitions
5. IF a user clicks the Mode_Switch rapidly multiple times, THEN THE Transition_Engine SHALL debounce or queue the transitions to prevent animation conflicts

### Requirement 4: Smooth Opacity Transitions

**User Story:** As a user, I want elements to fade in and out smoothly during mode switching, so that the transition feels elegant and not abrupt.

#### Acceptance Criteria

1. WHEN elements are appearing, THE Transition_Engine SHALL animate opacity from 0 to 1 using a linear or ease-out timing function
2. WHEN elements are disappearing, THE Transition_Engine SHALL animate opacity from 1 to 0 using a linear or ease-in timing function
3. THE Transition_Engine SHALL ensure opacity transitions are synchronized with height and transform transitions
4. THE Chat_Log SHALL reach full opacity (1.0) within 350ms of becoming visible
5. THE Chat_Input_Row SHALL reach full opacity (1.0) within 350ms of becoming visible

### Requirement 5: Transform Animation Coordination

**User Story:** As a user, I want elements to slide smoothly into position during mode transitions, so that the animation feels three-dimensional and dynamic.

#### Acceptance Criteria

1. WHEN elements are appearing, THE Transition_Engine SHALL animate translateY from 8px to 0px
2. WHEN elements are disappearing, THE Transition_Engine SHALL animate translateY from 0px to -8px (for Auto_Mode elements) or 8px (for Chat_Mode elements)
3. THE Transition_Engine SHALL synchronize translateY animations with opacity changes so elements fade while moving
4. THE Transition_Engine SHALL use the same easing function for transform as for height transitions
5. THE Transition_Engine SHALL complete all transform animations within 400ms

### Requirement 6: Mode Switch Button Feedback

**User Story:** As a user, I want immediate visual feedback when I click the mode switch button, so that I know my action was registered.

#### Acceptance Criteria

1. WHEN the user clicks a mode button, THE Mode_Switch SHALL update the active button styling within 50ms
2. WHEN the mode changes, THE Mode_Switch slider SHALL animate to the new position within 350ms using a bouncy easing function (cubic-bezier(0.34, 1.56, 0.64, 1))
3. THE Mode_Switch SHALL disable button clicks during an active transition to prevent conflicts
4. THE Mode_Switch SHALL re-enable button clicks after the transition completes
5. THE Mode_Switch SHALL provide visual hover feedback (color change) within 100ms of mouse hover

### Requirement 7: Preserve User Context During Transition

**User Story:** As a user, I want my chat history and input to be preserved when switching modes, so that I don't lose my work or context.

#### Acceptance Criteria

1. WHEN switching from Chat_Mode to Auto_Mode, THE Transition_Engine SHALL preserve the Chat_Log content in the DOM
2. WHEN switching from Auto_Mode to Chat_Mode, THE Transition_Engine SHALL restore the Chat_Log scroll position to the most recent message
3. WHEN switching modes, THE Transition_Engine SHALL preserve the buddy avatar, name, and status display without re-rendering
4. THE Transition_Engine SHALL maintain localStorage state for the selected mode throughout the transition
5. IF the user has typed text in Chat_Input_Row and switches to Auto_Mode, THEN THE Transition_Engine SHALL clear the input field

### Requirement 8: Responsive Transition Performance

**User Story:** As a user, I want mode transitions to perform smoothly on various devices, so that the experience is consistent regardless of my hardware.

#### Acceptance Criteria

1. THE Transition_Engine SHALL use CSS transitions instead of JavaScript animations for better performance
2. THE Transition_Engine SHALL use GPU-accelerated properties (transform, opacity) for animations
3. THE Transition_Engine SHALL avoid animating layout properties (width, height, margin, padding) directly when possible
4. WHEN the transition is active, THE Transition_Engine SHALL maintain a frame rate of at least 30 FPS on devices with moderate performance
5. THE Transition_Engine SHALL complete all transitions within 500ms total duration to maintain perceived responsiveness

### Requirement 9: Accessibility During Transitions

**User Story:** As a user relying on assistive technologies, I want mode transitions to be announced and accessible, so that I can understand what is happening.

#### Acceptance Criteria

1. WHEN the mode changes, THE Mode_Switch SHALL update ARIA attributes (aria-pressed or aria-selected) to reflect the current state
2. THE Mode_Switch buttons SHALL have descriptive aria-label or title attributes ("Chat Otomatis" and "Chat Input")
3. WHEN Chat_Mode is activated, THE Chat_Input_Row SHALL receive focus automatically after the transition completes
4. THE Transition_Engine SHALL ensure keyboard navigation works correctly during and after transitions
5. THE Transition_Engine SHALL announce mode changes to screen readers using aria-live regions or role="status"

### Requirement 10: Smooth Settings Button Transition

**User Story:** As a user, I want the settings button (cog icon) to smoothly disappear in Chat_Mode and reappear in Auto_Mode, so that the interface feels cohesive.

#### Acceptance Criteria

1. WHEN switching to Chat_Mode, THE Settings_Button SHALL animate width and height from 30px to 0px over 300ms
2. WHEN switching to Chat_Mode, THE Settings_Button SHALL fade out (opacity 1 to 0) over 300ms
3. WHEN switching to Auto_Mode, THE Settings_Button SHALL animate width and height from 0px to 30px over 300ms
4. WHEN switching to Auto_Mode, THE Settings_Button SHALL fade in (opacity 0 to 1) over 300ms
5. THE Settings_Button SHALL maintain its circular shape throughout the transition without distortion
