package com.digitaldarkspot;

import java.util.ArrayList;
import java.util.List;

/**
 * Starter service. Replace TODOs with real extraction and AI comparison.
 * Absence from reviewed sources is not proof that information does not exist.
 */
public class AnalysisService {
    public ResearchModels.AnalysisResponse analyze(ResearchModels.ResearchRequest request) {
        if (request == null || request.question() == null || request.question().isBlank()) {
            throw new IllegalArgumentException("A research question is required.");
        }
        List<ResearchModels.Finding> findings = new ArrayList<>();
        List<String> checklist = new ArrayList<>();
        // TODO 1: Collect permitted sources.
        // TODO 2: Extract claims and exact source passages/page references.
        // TODO 3: Break the question into answerable requirements.
        // TODO 4: Detect unanswered requirements, possible conflicts and stale dates.
        // TODO 5: Validate AI output and retain uncertainty.
        findings.add(new ResearchModels.Finding(
            "POTENTIAL_GAP", "Analysis service not connected",
            "Connect source extraction and an AI provider to produce real findings.",
            "NEEDS_VERIFICATION", List.of()
        ));
        checklist.add("Connect a permitted source collection and extraction service.");
        checklist.add("Verify claims against original sources.");
        checklist.add("Check dates and the current application or research cycle.");
        return new ResearchModels.AnalysisResponse(
            request.question(), findings, checklist,
            "AI findings are verification signals, not guaranteed facts."
        );
    }
}