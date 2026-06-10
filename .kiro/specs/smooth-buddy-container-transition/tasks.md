# Implementation Plan: Smooth Buddy Container Transition

## Overview

This implementation plan breaks down the smooth buddy container transition feature into discrete coding tasks across 6 phases: Foundation, Core Transitions, Polish & Performance, Accessibility, Testing, and Refinement. The feature enhances the mode switching animation between automatic chat mode (Chat Otomatis) and chat input mode (Chat Input) with coordinated timing, GPU-accelerated animations, and accessibility support.

## Tasks

- [ ] 1. Phase 1: Foundation - Set up core infrastructure
  - [-] 1.1 Create BuddyStateManager class in dashboard.blade.php
    - Implement constructor with currentMode, chatHistory, scrollPosition properties
    - Implement getCurrentMode() method with localStorage fallback
    - Implement saveMode(mode) method with localStorage persistence
    - Implement saveScrollPosition() and restoreScrollPosition() methods
    - Implement clearChatInput() method
    - Add error handling for localStorage unavailability (try-catch with fallback)
    - _Requirements: 7.1, 7.2, 7.4, 7.5_

  - [~] 1.2 Create BuddyTransitionCoordinator class structure in dashboard.blade.php
    - Implement constructor with config object (phaseDuration, staggerDelay, debounceDelay, easingFunction)
    - Add isTransitioning flag and transitionQueue array properties
    - Implement debouncedSwitch(mode) method with 500ms debounce logic
    - Add transition lock mechanism to prevent concurrent transitions
    - Implement basic switchMode(targetMode) method skeleton
    - _Requirements: 3.5, 6.3, 6.4_

  - [-] 1.3 Define base CSS transition classes in public/css/dashboard.css
    - Create .buddy-transition-element class with transition-property, timing-function, will-change
    - Create .buddy-element-fadeout class with opacity 0, translateY(-8px), pointer-events none
    - Create .buddy-element-fadein class with opacity 1, translateY(0), pointer-events auto
    - Create .buddy-element-collapsed class with max-height 0, margin 0, padding 0, overflow hidden
    - Create .buddy-element-expanded class with overflow visible
    - Set transition durations: fadeout 300ms, fadein 350ms
    - _Requirements: 1.1, 1.2, 2.4, 4.1, 4.2, 5.1, 5.2_

  - [ ] 1.4 Initialize transition configuration object in dashboard.blade.php
    - Define transitionConfig with timing properties (fadeOutDuration, fadeInDuration, heightDuration, etc.)
    - Define easing functions for height, opacity, transform, slider
    - Define transform values (fadeOutY: -8, fadeInY: 8, restY: 0)
    - Define element-specific configurations (typingText, chatLog, chatInput, promptWrapper, pillsRow, settingsBtn)
    - _Requirements: 1.1, 1.2, 1.3, 2.3, 2.4, 2.5_

- [~] 2. Checkpoint - Verify foundation setup
  - Ensure all tests pass, ask the user if questions arise.


- [ ] 3. Phase 2: Core Transitions - Implement animation phases
  - [~] 3.1 Implement fadeOutCurrentMode(currentMode) method
    - Query all elements for current mode (typingText, promptWrapper, pillsRow for auto; chatLog, chatInput for chat)
    - Apply .buddy-element-fadeout class to each element with stagger delay (100ms between elements)
    - Set pointer-events to none on fading elements
    - Return Promise that resolves when all fade-out transitions complete
    - Add null checks for missing DOM elements with error logging
    - _Requirements: 2.1, 2.2, 2.3, 3.2, 4.1, 4.2_

  - [~] 3.2 Implement adjustContainerHeight() method
    - Measure current container height using offsetHeight
    - Calculate target height based on new mode's visible elements
    - Apply height transition with cubic-bezier(0.16, 1, 0.3, 1) easing over 400ms
    - Maintain container width throughout transition (verify width invariant)
    - Return Promise that resolves when height transition completes
    - Add timeout fallback (500ms) to force completion if transitionend doesn't fire
    - _Requirements: 1.1, 1.2, 1.3, 1.4, 1.5_

  - [~] 3.3 Implement fadeInNewMode(targetMode) method
    - Query all elements for target mode (typingText, promptWrapper, pillsRow for auto; chatLog, chatInput for chat)
    - Remove .buddy-element-collapsed class to expand elements
    - Apply .buddy-element-fadein class with stagger delay (100ms between elements)
    - Restore pointer-events to auto when opacity reaches 0.8 or higher
    - Return Promise that resolves when all fade-in transitions complete
    - _Requirements: 2.1, 2.2, 2.3, 3.3, 4.1, 4.2_

  - [~] 3.4 Complete switchMode(targetMode) orchestration method
    - Check isTransitioning flag, return early if true
    - Set isTransitioning to true and record transitionStartTime
    - Get current mode from StateManager
    - Call fadeOutCurrentMode(currentMode) and await completion
    - Call adjustContainerHeight() and await completion
    - Call fadeInNewMode(targetMode) and await completion
    - Update StateManager with new mode and persist to localStorage
    - Set isTransitioning to false
    - Ensure total duration does not exceed 500ms (add timeout enforcement)
    - _Requirements: 3.5, 7.4, 8.5_

  - [~] 3.5 Wire mode switch buttons to transition coordinator
    - Add click event listeners to #btn-mode-auto and #btn-mode-chat buttons
    - Call debouncedSwitch('auto') or debouncedSwitch('chat') on click
    - Update active button styling within 50ms of click
    - Disable buttons during transition (set pointer-events none or disabled attribute)
    - Re-enable buttons after transition completes
    - _Requirements: 6.1, 6.3, 6.4_


- [~] 4. Checkpoint - Verify core transitions work
  - Ensure all tests pass, ask the user if questions arise.

- [ ] 5. Phase 3: Polish & Performance - Optimize animations
  - [~] 5.1 Add GPU acceleration optimizations to CSS
    - Add transform: translateZ(0) to .buddy-card-container for composite layer
    - Add backface-visibility: hidden to prevent flickering
    - Ensure all animations use only transform and opacity (GPU-accelerated properties)
    - Verify no animations use width, height, margin, padding directly
    - _Requirements: 8.1, 8.2, 8.3_

  - [~] 5.2 Implement will-change optimization
    - Add will-change: transform, opacity to .buddy-transition-element class
    - Remove will-change (set to auto) after transitions complete in switchMode()
    - Add .transition-complete class that sets will-change: auto
    - Apply .transition-complete after all animations finish
    - _Requirements: 8.2, 8.3_

  - [~] 5.3 Add CSS containment for layout isolation
    - Add contain: layout style paint to .buddy-card-container
    - Add contain: layout style to .buddy-chat-log
    - Verify containment doesn't break existing layout
    - _Requirements: 8.3, 8.4_

  - [~] 5.4 Implement requestAnimationFrame batching for DOM operations
    - Refactor adjustContainerHeight() to batch DOM reads in one rAF, writes in another
    - Refactor fadeOutCurrentMode() to batch class additions in single rAF
    - Refactor fadeInNewMode() to batch class additions in single rAF
    - Prevent layout thrashing by separating reads and writes
    - _Requirements: 8.3, 8.4_

  - [~] 5.5 Add performance monitoring instrumentation
    - Implement frame rate tracking using requestAnimationFrame during transitions
    - Add Performance API marks and measures (transition-start, transition-end)
    - Log transition duration and frame rate to console (dev mode only)
    - Verify frame rate maintains 30+ FPS throughout transition
    - _Requirements: 8.4, 8.5_

  - [~] 5.6 Implement Settings button smooth transition
    - Add CSS transitions for Settings button width, height, opacity (300ms duration)
    - Animate from 30px to 0px when switching to Chat mode
    - Animate from 0px to 30px when switching to Auto mode
    - Maintain border-radius: 50% and aspect ratio 1.0 throughout transition
    - Synchronize with mode switch timing
    - _Requirements: 10.1, 10.2, 10.3, 10.4, 10.5_

  - [~] 5.7 Implement mode switch slider animation
    - Add CSS transition for slider transform with cubic-bezier(0.34, 1.56, 0.64, 1) easing
    - Set transition duration to 350ms
    - Animate slider position when mode changes
    - Ensure bouncy easing creates smooth, playful effect
    - _Requirements: 6.2_


- [~] 6. Checkpoint - Verify performance optimizations
  - Ensure all tests pass, ask the user if questions arise.

- [ ] 7. Phase 4: Accessibility - Add accessibility support
  - [~] 7.1 Create BuddyAccessibilityManager class in dashboard.blade.php
    - Implement constructor
    - Implement updateModeButtonAria(activeMode) method
    - Implement announceModeChange(newMode) method
    - Implement manageFocus(newMode) method
    - Implement maintainKeyboardNav() method
    - _Requirements: 9.1, 9.3, 9.4, 9.5_

  - [~] 7.2 Implement ARIA attribute updates
    - Update aria-pressed or aria-selected on mode buttons within 50ms of mode change
    - Add aria-label or title attributes to mode buttons ("Chat Otomatis", "Chat Input")
    - Ensure ARIA attributes reflect current active mode accurately
    - _Requirements: 9.1, 9.2_

  - [~] 7.3 Implement focus management after transitions
    - Focus Chat_Input_Row automatically when switching to Chat mode
    - Set focus within 50ms after transition completes
    - Check if element is focusable before setting focus (has tabindex or naturally focusable)
    - Add error handling for non-focusable elements (skip focus, log warning)
    - _Requirements: 9.3_

  - [~] 7.4 Add screen reader announcements
    - Create or identify aria-live region or element with role="status"
    - Update text content to announce mode change within 50ms
    - Announce "Switched to Chat Otomatis mode" or "Switched to Chat Input mode"
    - Add null check for aria-live element with error logging
    - _Requirements: 9.5_

  - [~] 7.5 Ensure keyboard navigation works during transitions
    - Test Tab, Enter, Space key handling during and after transitions
    - Ensure keyboard events are not blocked during transitions
    - Verify focus order remains logical after mode switch
    - Add event listener tests for keyboard navigation
    - _Requirements: 9.4_

  - [~] 7.6 Integrate AccessibilityManager with TransitionCoordinator
    - Call updateModeButtonAria() at start of switchMode()
    - Call announceModeChange() after mode switch completes
    - Call manageFocus() after fadeInNewMode() completes
    - Ensure accessibility updates don't block transition performance
    - _Requirements: 9.1, 9.3, 9.5_

  - [~] 7.7 Add hover feedback for mode buttons
    - Add CSS :hover styles for mode buttons with color change
    - Ensure hover styling applies within 100ms of mouse hover
    - Test hover feedback on both auto and chat mode buttons
    - _Requirements: 6.5_


- [~] 8. Checkpoint - Verify accessibility features
  - Ensure all tests pass, ask the user if questions arise.

- [ ] 9. Phase 5: Testing - Implement comprehensive test suite
  - [~] 9.1 Set up testing infrastructure
    - Install fast-check library for property-based testing (npm install --save-dev fast-check)
    - Install Jest or Mocha test framework if not already present
    - Create test file: tests/buddy-transition.test.js
    - Set up test environment with DOM mocking (jsdom)
    - _Requirements: All_

  - [ ]* 9.2 Write property tests for timing properties (Properties 1, 5, 6, 7, 26)
    - **Property 1: Bidirectional Container Height Transition Duration**
    - **Validates: Requirements 1.1, 1.2**
    - Test that container height transition completes within 350-500ms for any mode switch
    - Use fc.constantFrom('auto', 'chat') for mode generation
    - Run 100 iterations minimum

  - [ ]* 9.3 Write property tests for invariant properties (Properties 2, 3, 11, 22, 33)
    - **Property 2: Container Width Invariant**
    - **Validates: Requirements 1.4**
    - **Property 3: Layout Stability Invariant**
    - **Validates: Requirements 1.5**
    - **Property 11: Text Layout Stability**
    - **Validates: Requirements 3.4**
    - **Property 22: Static Element Preservation**
    - **Validates: Requirements 7.3**
    - **Property 33: Settings Button Shape Preservation**
    - **Validates: Requirements 10.5**
    - Test that width, layout positions, text lines, DOM references, and button shape remain unchanged
    - Run 100 iterations minimum per property

  - [ ]* 9.4 Write property tests for animation sequencing (Properties 4, 8, 9, 10, 14, 15)
    - **Property 4: Sequential Animation Ordering**
    - **Validates: Requirements 2.1, 2.2**
    - **Property 8: Overflow Control During Collapse**
    - **Validates: Requirements 3.1**
    - **Property 9: Pointer Events During Fade Out**
    - **Validates: Requirements 3.2**
    - **Property 10: Pointer Events Restoration Timing**
    - **Validates: Requirements 3.3**
    - **Property 14: Multi-Property Synchronization**
    - **Validates: Requirements 4.3, 5.3**
    - **Property 15: Transform Animation Direction**
    - **Validates: Requirements 5.1, 5.2**
    - Test fade-out before fade-in, overflow hidden, pointer-events timing, synchronization, transform direction
    - Run 100 iterations minimum per property

  - [ ]* 9.5 Write property tests for state management (Properties 20, 21, 23, 24)
    - **Property 20: Chat Log Content Preservation**
    - **Validates: Requirements 7.1**
    - **Property 21: Scroll Position Restoration**
    - **Validates: Requirements 7.2**
    - **Property 23: LocalStorage Persistence**
    - **Validates: Requirements 7.4**
    - **Property 24: Input Clearing on Mode Switch**
    - **Validates: Requirements 7.5**
    - Test DOM preservation, scroll position, localStorage updates, input clearing
    - Run 100 iterations minimum per property


  - [ ]* 9.6 Write property tests for accessibility (Properties 27, 28, 29, 30)
    - **Property 27: ARIA Attribute Updates**
    - **Validates: Requirements 9.1**
    - **Property 28: Focus Management After Transition**
    - **Validates: Requirements 9.3**
    - **Property 29: Keyboard Navigation Preservation**
    - **Validates: Requirements 9.4**
    - **Property 30: Screen Reader Announcements**
    - **Validates: Requirements 9.5**
    - Test ARIA updates, focus management, keyboard events, screen reader announcements
    - Run 100 iterations minimum per property

  - [ ]* 9.7 Write property tests for interaction properties (Properties 12, 13, 16, 17, 18, 19)
    - **Property 12: Transition Debouncing**
    - **Validates: Requirements 3.5**
    - **Property 13: Opacity Animation Direction and Easing**
    - **Validates: Requirements 4.1, 4.2**
    - **Property 16: Mode Button Responsiveness**
    - **Validates: Requirements 6.1**
    - **Property 17: Slider Animation Timing and Easing**
    - **Validates: Requirements 6.2**
    - **Property 18: Mode Button Interaction Lock Round-Trip**
    - **Validates: Requirements 6.3, 6.4**
    - **Property 19: Hover Feedback Responsiveness**
    - **Validates: Requirements 6.5**
    - Test debouncing, opacity easing, button responsiveness, slider animation, interaction lock, hover feedback
    - Run 100 iterations minimum per property

  - [ ]* 9.8 Write property tests for performance (Properties 25, 31, 32)
    - **Property 25: Frame Rate Performance**
    - **Validates: Requirements 8.4**
    - **Property 31: Settings Button Size Animation Round-Trip**
    - **Validates: Requirements 10.1, 10.3**
    - **Property 32: Settings Button Opacity Animation Round-Trip**
    - **Validates: Requirements 10.2, 10.4**
    - Test frame rate maintains 30+ FPS, settings button size and opacity animations
    - Run 100 iterations minimum per property

  - [ ]* 9.9 Write unit tests for CSS configuration
    - Test container uses cubic-bezier(0.16, 1, 0.3, 1) easing function
    - Test mode buttons have descriptive ARIA labels ("Chat Otomatis", "Chat Input")
    - Test transition durations are set correctly (fadeout 300ms, fadein 350ms)
    - Test Settings button has border-radius 50%
    - Test will-change properties are set correctly
    - _Requirements: 1.3, 9.2_

  - [ ]* 9.10 Write unit tests for error handling
    - Test localStorage unavailable scenario (QuotaExceededError)
    - Test invalid mode value in localStorage
    - Test element not found in DOM during transition
    - Test focus target element not focusable
    - Test ARIA live region not found
    - Test transition timeout fallback (transitionend doesn't fire)
    - _Requirements: All error scenarios_

  - [ ]* 9.11 Write unit tests for edge cases
    - Test rapid clicks exactly 500ms apart
    - Test transition with empty chat log
    - Test mode switch when already in target mode
    - Test transition interruption and recovery
    - Test browser without localStorage support
    - _Requirements: 3.5, 7.1_


- [~] 10. Checkpoint - Verify all tests pass
  - Ensure all tests pass, ask the user if questions arise.

- [ ] 11. Phase 6: Refinement - Polish and cross-browser testing
  - [~] 11.1 Implement context preservation during transitions
    - Ensure chat history is preserved in DOM when switching from Chat to Auto mode
    - Verify buddy avatar, name, and status display are not re-rendered
    - Test that DOM node references remain the same before and after transition
    - _Requirements: 7.1, 7.3_

  - [~] 11.2 Implement scroll position management
    - Save chat log scroll position before switching from Chat to Auto mode
    - Restore scroll position to bottom (most recent message) when switching to Chat mode
    - Use scrollTop and scrollHeight properties
    - _Requirements: 7.2_

  - [~] 11.3 Add overflow control during transitions
    - Ensure overflow: hidden is set on collapsing elements
    - Prevent content overflow during height transitions
    - Restore overflow: visible after expansion completes
    - _Requirements: 3.1_

  - [~] 11.4 Implement text layout stability
    - Prevent text reflow or wrapping changes during height transitions
    - Use fixed widths or min-width to maintain text layout
    - Test with various text lengths and container sizes
    - _Requirements: 3.4_

  - [~] 11.5 Add transition timeout fallback
    - Implement 500ms timeout for each transition phase
    - Force transition to end state if timeout expires
    - Apply final CSS classes directly on timeout
    - Log warning when timeout fallback is triggered
    - _Requirements: 8.5_

  - [~] 11.6 Perform cross-browser testing
    - Test on Chrome 90+, Firefox 88+, Safari 14+, Edge 90+
    - Verify animations work smoothly on all browsers
    - Test fallback behavior for older browsers (graceful degradation to instant switching)
    - Document any browser-specific issues and workarounds
    - _Requirements: All_

  - [~] 11.7 Add memory cleanup and event listener management
    - Implement destroy() method in TransitionCoordinator
    - Clear timers (debounceTimer) on cleanup
    - Remove event listeners on cleanup
    - Clear object references to prevent memory leaks
    - _Requirements: 8.4_

  - [~] 11.8 Optimize passive event listeners
    - Add { passive: true } to scroll event listeners on chat log
    - Add { passive: true } to touch event listeners on mode buttons
    - Improve scroll and touch performance
    - _Requirements: 8.4_

  - [~] 11.9 Add reduced motion support
    - Detect prefers-reduced-motion media query
    - Disable or reduce animations when user prefers reduced motion
    - Provide instant mode switching as fallback
    - _Requirements: 9.4_


  - [~] 11.10 Final integration and polish
    - Test complete end-to-end mode switching flow
    - Verify all requirements are met
    - Test with real user interactions (clicks, keyboard, touch)
    - Verify performance metrics (frame rate, duration, layout recalculations)
    - Document any known limitations or future improvements
    - _Requirements: All_

- [~] 12. Final checkpoint - Complete feature verification
  - Ensure all tests pass, ask the user if questions arise.

## Notes

- Tasks marked with `*` are optional and can be skipped for faster MVP
- Each task references specific requirements for traceability
- Checkpoints ensure incremental validation at the end of each phase
- Property tests validate universal correctness properties across all mode switches
- Unit tests validate specific configurations, error handling, and edge cases
- The implementation uses JavaScript for logic and CSS for animations
- GPU-accelerated properties (transform, opacity) are used for optimal performance
- Accessibility features ensure keyboard navigation and screen reader support
- Cross-browser testing ensures compatibility with modern browsers (Chrome 90+, Firefox 88+, Safari 14+, Edge 90+)

## Task Dependency Graph

```json
{
  "waves": [
    { "id": 0, "tasks": ["1.1", "1.3", "1.4"] },
    { "id": 1, "tasks": ["1.2"] },
    { "id": 2, "tasks": ["3.1", "3.2", "3.3"] },
    { "id": 3, "tasks": ["3.4"] },
    { "id": 4, "tasks": ["3.5", "5.1", "5.3"] },
    { "id": 5, "tasks": ["5.2", "5.4", "5.6", "5.7"] },
    { "id": 6, "tasks": ["5.5", "7.1"] },
    { "id": 7, "tasks": ["7.2", "7.7"] },
    { "id": 8, "tasks": ["7.3", "7.4", "7.5"] },
    { "id": 9, "tasks": ["7.6", "11.1", "11.2", "11.3", "11.4"] },
    { "id": 10, "tasks": ["11.5", "11.7", "11.8", "11.9"] },
    { "id": 11, "tasks": ["9.1"] },
    { "id": 12, "tasks": ["9.2", "9.3", "9.4", "9.5", "9.6", "9.7", "9.8", "9.9", "9.10", "9.11"] },
    { "id": 13, "tasks": ["11.6", "11.10"] }
  ]
}
```
