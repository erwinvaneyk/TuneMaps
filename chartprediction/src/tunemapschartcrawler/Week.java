package tunemapschartcrawler;

/**
 * A week
 * 
 * @author Rolf Jagerman <rolf.jagerman@contended.nl>
 */
public class Week {
    
    /**
     * The index
     */
    private int index;
    
    /**
     * The start time stamp
     */
    private int start;
    
    /**
     * The end time stamp
     */
    private int end;
    
    /**
     * The week step size
     */
    public static int STEP = 604800;
    
    /**
     * Creates a week
     * 
     * @param start The start time stamp
     * @param end The end time stamp
     */
    public Week(int start, int end) {
        this.start = start;
        this.end = end;
    }
    
    /**
     * @return the start
     */
    public int getStart() {
        return start;
    }

    /**
     * @param start the start to set
     */
    public void setStart(int start) {
        this.start = start;
    }

    /**
     * @return the end
     */
    public int getEnd() {
        return end;
    }

    /**
     * @param end the end to set
     */
    public void setEnd(int end) {
        this.end = end;
    }

    /**
     * @return the index
     */
    public int getIndex() {
        return index;
    }

    /**
     * @param index the index to set
     */
    public void setIndex(int index) {
        this.index = index;
    }
    
}
