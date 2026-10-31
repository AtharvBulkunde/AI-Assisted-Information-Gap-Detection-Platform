package com.digitaldarkspot;

import java.util.List;

public final class ResearchModels {
    private ResearchModels() {}
    public record ResearchRequest(String question, List<String> sourceUrls) {}
    public record Evidence(String sourceUrl, String excerpt, String publishedDate) {}
    public record Finding(String category, String title, String explanation, String status, List<Evidence> evidence) {}
    public record AnalysisResponse(String question, List<Finding> findings, List<String> verificationChecklist, String disclaimer) {}
}
