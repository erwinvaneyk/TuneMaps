package tunemapschartcrawler;

/**
 * A chart
 * 
 * @author Rolf Jagerman <rolf.jagerman@contended.nl>
 */
public class Chart {
    
    /**
     * The songs
     */
    private Song[] songs;
    
    /**
     * Creates the chart
     */
    public Chart() {
        songs = new Song[50];
    }
    
    /**
     * Sets a song
     * 
     * @param song The song
     * @param rank The rank
     */
    public void setSong(Song song, int rank) {
        songs[rank-1] = song;
    }
    
    /**
     * Gets a song
     * 
     * @param rank The rank
     * @return The song
     */
    public Song getSong(int rank) {
        return songs[rank-1];
    }
    
    /**
     * Gets all songs
     * 
     * @return The songs
     */
    public Song[] getSongs() {
        return songs;
    }
    
    /**
     * Gets the rank of a song
     * 
     * @param song The song
     * @return The rank
     */
    public int getRank(Song song) {
        for(int i=0; i<50; i++) {
            if(song.equals(songs[i])) {
                return i+1;
            }
        }
        return 0;
    }
    
}
