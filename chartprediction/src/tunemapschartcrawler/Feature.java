package tunemapschartcrawler;

/**
 * A feature vector
 * 
 * @author Rolf Jagerman <rolf.jagerman@contended.nl>
 */
public class Feature {
    
    /**
     * The week
     */
    private int week;
    
    /**
     * The feature input
     */
    private String values;
    
    /**
     * The feature output
     */
    private String output;
    
    /**
     * Creates a feature from a single string
     * 
     * @param input The input
     * @param withOutput Whether the string also contains output
     */
    public Feature(String input, boolean withOutput) {
        loadFromString(input, withOutput);
        this.output = "";
    }
    
    /**
     * Creates a feature
     * 
     * @param week The week
     * @param values The values
     */
    public Feature(int week, String values) {
        this.week = week;
        this.values = values;
        this.output = "";
    }

    /**
     * @return the week
     */
    public int getWeek() {
        return week;
    }

    /**
     * @param week the week to set
     */
    public void setWeek(int week) {
        this.week = week;
    }

    /**
     * @return the values
     */
    public String getValues() {
        return values;
    }

    /**
     * @param values the values to set
     */
    public void setValues(String values) {
        this.values = values;
    }
    
    /**
     * Loads the feature from a string
     * 
     * @param input The string
     * @param withOutput Whether the string also contains output
     */
    public final void loadFromString(String input, boolean withOutput) {
        String[] features = input.split(",");
        week = Integer.parseInt(features[0]);
        values = "";
        for(int i=1; i<features.length - (withOutput ? 1 : 0); i++) {
            if(i>1) {
                values += ",";
            }
            values += features[i];
        }
        if(withOutput) {
            setOutput(features[features.length-1]);
        }
    }
    
    /**
     * @return The string representation of the feature
     */
    @Override
    public String toString() {
        return week + "," + values + (output.isEmpty() ? "" : "," + output);
    }

    /**
     * @return the output
     */
    public String getOutput() {
        return output;
    }

    /**
     * @param output the output to set
     */
    public void setOutput(String output) {
        this.output = output;
    }
    
}
